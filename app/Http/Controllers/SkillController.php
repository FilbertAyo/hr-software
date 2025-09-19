<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $skills = Skill::all();

        return view("settings.skill", compact("skills"));
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
        $skill = Skill::create($request->all());

        return redirect()->back()->with('success','Skill added successfully');
    }

  
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'skill_name' => 'required|string|max:255',
        ]);

        // Find the skill by ID
        $skill = Skill::findOrFail($id);

        // Update the skill's name
        $skill->skill_name = $request->input('skill_name');

        // Save the updated skill
        $skill->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Skill updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skill = Skill::find($id);

    if ($skill) {
        $skill->delete();
        return redirect()->back()->with('success', 'Skill deleted successfully');
    } else {
        return redirect()->back()->with('error', 'Skill not found');
    }
    }
}
