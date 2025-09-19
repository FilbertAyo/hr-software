<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = company::all();

        return view("company.setup.index", compact("companies"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("company.setup.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $company = company::create($request->all());

        return redirect()->back()->with('success', 'company added successfully');
    }

    public function show(string $id)
    {
        $company = Company::find($id);
        return view('company.setup.show', compact('company'));
    }
    public function edit(string $id)
    {
        $company = Company::find($id);
        return view('company.setup.edit', compact('company'));
    }

    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'company' => 'required|string|max:255',
        ]);

        // Find the company by ID
        $company = company::findOrFail($id);

        // Update the company's name
        $company->company = $request->input('company');

        // Save the updated company
        $company->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'company updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = company::find($id);

        if ($company) {
            $company->delete();
            return redirect()->back()->with('success', 'company deleted successfully');
        } else {
            return redirect()->back()->with('error', 'company not found');
        }
    }

    
}
