<?php

namespace App\Http\Controllers;

use App\Models\Factor;
use App\Models\GeneralFactor;
use Illuminate\Http\Request;

class FactorController extends Controller
{
    public function index()
    {
        $factors = Factor::with('generalFactor')->get();
        $generalFactors = GeneralFactor::where('status', 'Active')->get();
        return view('performance.factors.index', compact('factors', 'generalFactors'));
    }

    public function create()
    {
        $generalFactors = GeneralFactor::where('status', 'Active')->get();
        return view('performance.factors.create', compact('generalFactors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'general_factor_id' => 'required|exists:general_factors,id',
            'factor_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:Active,Inactive'
        ]);

        Factor::create($request->all());

        return redirect()->route('factors.index')
                        ->with('success', 'Factor created successfully!');
    }

    public function show(Factor $factor)
    {
        $factor->load(['generalFactor', 'subFactors']);
        return view('performance.factors.show', compact('factor'));
    }

    public function edit(Factor $factor)
    {
        $generalFactors = GeneralFactor::where('status', 'Active')->get();
        return view('performance.factors.edit', compact('factor', 'generalFactors'));
    }

    public function update(Request $request, Factor $factor)
    {
        $request->validate([
            'general_factor_id' => 'required|exists:general_factors,id',
            'factor_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:Active,Inactive'
        ]);

        $factor->update($request->all());

        return redirect()->route('factors.index')
                        ->with('success', 'Factor updated successfully!');
    }

    public function destroy(Factor $factor)
    {
        if ($factor->subFactors()->count() > 0) {
            return redirect()->route('factors.index')
                            ->with('error', 'Cannot delete Factor that has associated sub-factors!');
        }

        $factor->delete();

        return redirect()->route('factors.index')
                        ->with('success', 'Factor deleted successfully!');
    }

    // API method for getting sub-factors by factor
    public function getSubFactors($id)
    {
        $subFactors = Factor::find($id)?->subFactors()->where('status', 'Active')->get();

        return response()->json($subFactors ?? []);
    }
}
