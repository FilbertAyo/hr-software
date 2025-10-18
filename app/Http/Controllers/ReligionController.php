<?php

namespace App\Http\Controllers;

use App\Models\Religion;
use Illuminate\Http\Request;

class ReligionController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $religions = Religion::all();

        return view("settings.religion", compact("religions"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $religion = Religion::create($request->all());

        return redirect()->back()->with('success','religion added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'religion_name' => 'required|string|max:255',
        ]);

        // Find the religion by ID
        $religion = Religion::findOrFail($id);

        // Update the religion's name
        $religion->religion_name = $request->input('religion_name');

        // Save the updated religion
        $religion->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'religion updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $religion = Religion::find($id);

        if ($religion) {
            $religion->delete();
            return redirect()->back()->with('success', 'religion deleted successfully');
        } else {
            return redirect()->back()->with('error', 'religion not found');
        }
    }
}
