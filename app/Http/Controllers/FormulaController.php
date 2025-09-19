<?php

namespace App\Http\Controllers;

use App\Models\Formula;
use Illuminate\Http\Request;

class FormulaController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formulas = formula::all();

        return view("settings.formula", compact("formulas"));
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
        $formula = formula::create($request->all());

        return redirect()->back()->with('success','formula added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'formula' => 'required|string|max:255',
        ]);

        // Find the formula by ID
        $formula = formula::findOrFail($id);

        // Update the formula's name
        $formula->formula = $request->input('formula');

        // Save the updated formula
        $formula->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'formula updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $formula = formula::find($id);

        if ($formula) {
            $formula->delete();
            return redirect()->back()->with('success', 'formula deleted successfully');
        } else {
            return redirect()->back()->with('error', 'formula not found');
        }
    }
}
