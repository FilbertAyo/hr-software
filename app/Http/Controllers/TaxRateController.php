<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    public function index()
    {
        $taxrates = taxrate::all();

        return view("settings.taxrate", compact("taxrates"));
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
        $taxrate = taxrate::create($request->all());

        return redirect()->back()->with('success','taxrate added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'taxrate' => 'required|string|max:255',
        ]);

        // Find the taxrate by ID
        $taxrate = taxrate::findOrFail($id);

        // Update the taxrate's name
        $taxrate->taxrate = $request->input('taxrate');

        // Save the updated taxrate
        $taxrate->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'taxrate updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $taxrate = taxrate::find($id);

        if ($taxrate) {
            $taxrate->delete();
            return redirect()->back()->with('success', 'taxrate deleted successfully');
        } else {
            return redirect()->back()->with('error', 'taxrate not found');
        }
    }
}
