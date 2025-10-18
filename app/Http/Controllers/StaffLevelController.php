<?php

namespace App\Http\Controllers;

use App\Models\StaffLevel;
use Illuminate\Http\Request;

class StaffLevelController extends Controller
{
    public function index()
    {
        $level_names = StaffLevel::all();

        return view("settings.stafflevel", compact("level_names"));
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
        // Validate the request data
        $request->validate([
            'level_name' => 'required|string|max:255',
            'level_order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        // Create the staff level with validated data
        $level_name = StaffLevel::create([
            'level_name' => $request->input('level_name'),
            'level_order' => $request->input('level_order', 0),
            'description' => $request->input('description'),
        ]);

        return redirect()->back()->with('success','Staff level added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'level_name' => 'required|string|max:255',
            'level_order' => 'nullable|integer|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        // Find the staff level by ID
        $level_name = StaffLevel::findOrFail($id);

        // Update the staff level with validated data
        $level_name->update([
            'level_name' => $request->input('level_name'),
            'level_order' => $request->input('level_order', 0),
            'description' => $request->input('description'),
        ]);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Staff level updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $level_name = StaffLevel::findOrFail($id);
            $level_name->delete();
            return redirect()->back()->with('success', 'Staff level deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting staff level: ' . $e->getMessage());
        }
    }
}
