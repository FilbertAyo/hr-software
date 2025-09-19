<?php

namespace App\Http\Controllers;

use App\Models\GeneralFactor;
use Illuminate\Http\Request;

class GeneralFactorController extends Controller
{
    public function index()
    {
        $generalFactors = GeneralFactor::withCount('factors')->get();
        return view('performance.general-factors.index', compact('generalFactors'));
    }

    public function create()
    {
        return view('performance.general-factors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'general_factor_name' => 'required|string|max:255|unique:general_factors',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        GeneralFactor::create($request->all());

        return redirect()->route('general-factors.index')
                        ->with('success', 'General Factor created successfully!');
    }

    public function show(GeneralFactor $generalFactor)
    {
        $generalFactor->load(['factors', 'subFactors']);
        return view('performance.general-factors.show', compact('generalFactor'));
    }

    public function edit(GeneralFactor $generalFactor)
    {
        return view('performance.general-factors.edit', compact('generalFactor'));
    }

    public function update(Request $request, GeneralFactor $generalFactor)
    {
        $request->validate([
            'general_factor_name' => 'required|string|max:255|unique:general_factors,general_factor_name,' . $generalFactor->id,
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive'
        ]);

        $generalFactor->update($request->all());

        return redirect()->route('general-factors.index')
                        ->with('success', 'General Factor updated successfully!');
    }

    public function destroy(GeneralFactor $generalFactor)
    {
        if ($generalFactor->factors()->count() > 0) {
            return redirect()->route('general-factors.index')
                            ->with('error', 'Cannot delete General Factor that has associated factors!');
        }

        $generalFactor->delete();

        return redirect()->route('general-factors.index')
                        ->with('success', 'General Factor deleted successfully!');
    }

    // API method for getting factors by general factor
    public function getFactors($id)
    {
        $factors = GeneralFactor::find($id)?->factors()->where('status', 'Active')->get();

        return response()->json($factors ?? []);
    }
}
