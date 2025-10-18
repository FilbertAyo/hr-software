<?php

namespace App\Http\Controllers;

use App\Models\TaxTable;
use Illuminate\Http\Request;

class taxtableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taxtables = TaxTable::all();

        return view("settings.taxtable", compact("taxtables"));
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
        $taxtable = TaxTable::create($request->all());

        return redirect()->back()->with('success','taxtable added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'taxtable' => 'required|string|max:255',
        ]);

        // Find the taxtable by ID
        $taxtable = TaxTable::findOrFail($id);

        // Update the taxtable's name
        $taxtable->taxtable = $request->input('taxtable');

        // Save the updated taxtable
        $taxtable->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'taxtable updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $taxtable = TaxTable::find($id);

        if ($taxtable) {
            $taxtable->delete();
            return redirect()->back()->with('success', 'taxtable deleted successfully');
        } else {
            return redirect()->back()->with('error', 'taxtable not found');
        }
    }
}
