<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $languages = Language::all();

        return view("settings.language", compact("languages"));
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
        $language = Language::create($request->all());

        return redirect()->back()->with('success','language added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'language' => 'required|string|max:255',
        ]);

        // Find the language by ID
        $language = language::findOrFail($id);

        // Update the language's name
        $language->language = $request->input('language');

        // Save the updated language
        $language->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'language updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $language = language::find($id);

        if ($language) {
            $language->delete();
            return redirect()->back()->with('success', 'language deleted successfully');
        } else {
            return redirect()->back()->with('error', 'language not found');
        }
    }
}
