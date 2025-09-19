<?php

namespace App\Http\Controllers;

use App\Models\Termination;
use Illuminate\Http\Request;

class TerminationController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $terminations = termination::all();

        return view("settings.termination", compact("terminations"));
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
        $termination = termination::create($request->all());

        return redirect()->back()->with('success','termination added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'termination' => 'required|string|max:255',
        ]);

        // Find the termination by ID
        $termination = termination::findOrFail($id);

        // Update the termination's name
        $termination->termination = $request->input('termination');

        // Save the updated termination
        $termination->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'termination updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $termination = termination::find($id);

        if ($termination) {
            $termination->delete();
            return redirect()->back()->with('success', 'termination deleted successfully');
        } else {
            return redirect()->back()->with('error', 'termination not found');
        }
    }
}
