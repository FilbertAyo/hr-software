<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{

     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $educations = Education::all();

        return view("settings.education", compact("educations"));
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
        $education = Education::create($request->all());

        return redirect()->back()->with('success','education added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(education $education)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(education $education)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'education_level' => 'required|string|max:255',
        ]);

        $education = education::findOrFail($id);

        $education->education_level = $request->input('education_level');

        $education->save();
        return redirect()->back()->with('success', 'education updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $education = education::find($id);

        if ($education) {
            $education->delete();
            return redirect()->back()->with('success', 'education deleted successfully');
        } else {
            return redirect()->back()->with('error', 'education not found');
        }
    }

}
