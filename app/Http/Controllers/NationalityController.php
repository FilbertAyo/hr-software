<?php

namespace App\Http\Controllers;

use App\Models\Nationality;
use Illuminate\Http\Request;

class NationalityController extends Controller
{
    public function index()
    {
        $nationalitys = nationality::all();

        return view("settings.nationality", compact("nationalitys"));
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
        $nationality = nationality::create($request->all());

        return redirect()->back()->with('success','nationality added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'nationality' => 'required|string|max:255',
        ]);

        // Find the nationality by ID
        $nationality = nationality::findOrFail($id);

        // Update the nationality's name
        $nationality->nationality = $request->input('nationality');

        // Save the updated nationality
        $nationality->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'nationality updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $nationality = nationality::find($id);

        if ($nationality) {
            $nationality->delete();
            return redirect()->back()->with('success', 'nationality deleted successfully');
        } else {
            return redirect()->back()->with('error', 'nationality not found');
        }
    }
}
