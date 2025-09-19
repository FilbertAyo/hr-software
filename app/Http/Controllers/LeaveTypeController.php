<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leavetypes = leavetype::all();

        return view("attendance.leavetype", compact("leavetypes"));
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
        $leavetype = leavetype::create($request->all());

        return redirect()->back()->with('success', 'leavetype added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'leavetype' => 'required|string|max:255',
        ]);

        // Find the leavetype by ID
        $leavetype = leavetype::findOrFail($id);

        // Update the leavetype's name
        $leavetype->leavetype = $request->input('leavetype');

        // Save the updated leavetype
        $leavetype->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'leavetype updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $leavetype = leavetype::find($id);

        if ($leavetype) {
            $leavetype->delete();
            return redirect()->back()->with('success', 'leavetype deleted successfully');
        } else {
            return redirect()->back()->with('error', 'leavetype not found');
        }
    }
}
