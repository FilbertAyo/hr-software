<?php

namespace App\Http\Controllers;

use App\Models\Advance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AdvanceController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $advances = advance::all();
        $employees = Employee::all();

        return view("advance.index", compact("advances","employees"));
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $advance = advance::create($request->all());

        return redirect()->back()->with('success','advance added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'advance' => 'required|string|max:255',
        ]);

        // Find the advance by ID
        $advance = advance::findOrFail($id);

        // Update the advance's name
        $advance->advance = $request->input('advance');

        // Save the updated advance
        $advance->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'advance updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $advance = advance::find($id);

        if ($advance) {
            $advance->delete();
            return redirect()->back()->with('success', 'advance deleted successfully');
        } else {
            return redirect()->back()->with('error', 'advance not found');
        }
    }
}
