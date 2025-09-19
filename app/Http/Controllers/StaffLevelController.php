<?php

namespace App\Http\Controllers;

use App\Models\StaffLevel;
use Illuminate\Http\Request;

class StaffLevelController extends Controller
{
    public function index()
    {
        $stafflevels = stafflevel::all();

        return view("settings.stafflevel", compact("stafflevels"));
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
        $stafflevel = stafflevel::create($request->all());

        return redirect()->back()->with('success','stafflevel added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'stafflevel' => 'required|string|max:255',
        ]);

        // Find the stafflevel by ID
        $stafflevel = stafflevel::findOrFail($id);

        // Update the stafflevel's name
        $stafflevel->stafflevel = $request->input('stafflevel');

        // Save the updated stafflevel
        $stafflevel->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'stafflevel updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stafflevel = stafflevel::find($id);

        if ($stafflevel) {
            $stafflevel->delete();
            return redirect()->back()->with('success', 'stafflevel deleted successfully');
        } else {
            return redirect()->back()->with('error', 'stafflevel not found');
        }
    }
}
