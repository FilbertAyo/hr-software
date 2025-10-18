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
        $terminations = Termination::all();

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
        $termination = Termination::create($request->all());

        return redirect()->back()->with('success','termination added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'termination_type' => 'required|string|max:255',
        ]);

        // Find the termination by ID
        $termination = Termination::findOrFail($id);

        // Update the termination's name
        $termination->termination_type = $request->input('termination_type');

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
        $termination = Termination::find($id);

        if ($termination) {
            $termination->delete();
            return redirect()->back()->with('success', 'termination deleted successfully');
        } else {
            return redirect()->back()->with('error', 'termination not found');
        }
    }
}
