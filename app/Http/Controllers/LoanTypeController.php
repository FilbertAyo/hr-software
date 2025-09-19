<?php

namespace App\Http\Controllers;

use App\Models\LoanType;
use Illuminate\Http\Request;

class LoanTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loantypes = loantype::all();

        return view("loans.loantype", compact("loantypes"));
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
        $loantype = loantype::create($request->all());

        return redirect()->back()->with('success','loantype added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'loantype' => 'required|string|max:255',
        ]);

        // Find the loantype by ID
        $loantype = loantype::findOrFail($id);

        // Update the loantype's name
        $loantype->loantype = $request->input('loantype');

        // Save the updated loantype
        $loantype->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'loantype updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loantype = loantype::find($id);

        if ($loantype) {
            $loantype->delete();
            return redirect()->back()->with('success', 'loantype deleted successfully');
        } else {
            return redirect()->back()->with('error', 'loantype not found');
        }
    }
}
