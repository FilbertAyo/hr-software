<?php

namespace App\Http\Controllers;

use App\Models\SubFactor;
use App\Models\GeneralFactor;
use App\Models\Factor;
use Illuminate\Http\Request;

class SubFactorController extends Controller
{
    public function index()
    {
        $subFactors = SubFactor::with(['generalFactor', 'factor'])->get();
        $generalFactors = GeneralFactor::where('status', 'Active')->get();
        return view('performance.sub-factors.index', compact('subFactors', 'generalFactors'));
    }

    public function create()
    {
        $generalFactors = GeneralFactor::where('status', 'Active')->get();
        $factors = Factor::where('status', 'Active')->get();
        return view('performance.sub-factors.create', compact('generalFactors', 'factors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'general_factor_id' => 'required|exists:general_factors,id',
            'factor_id' => 'required|exists:factors,id',
            'sub_factor_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:Active,Inactive'
        ]);

        // Validate that factor belongs to the selected general factor
        $factor = Factor::find($request->factor_id);
        if ($factor->general_factor_id != $request->general_factor_id) {
            return back()->withErrors(['factor_id' => 'Selected factor does not belong to the selected general factor.']);
        }

        SubFactor::create($request->all());

        return redirect()->route('sub-factors.index')
                        ->with('success', 'Sub Factor created successfully!');
    }

    public function show(SubFactor $subFactor)
    {
        $subFactor->load(['generalFactor', 'factor']);
        return view('performance.sub-factors.show', compact('subFactor'));
    }

    public function edit(SubFactor $subFactor)
    {
        $generalFactors = GeneralFactor::where('status', 'Active')->get();
        $factors = Factor::where('status', 'Active')->get();
        return view('performance.sub-factors.edit', compact('subFactor', 'generalFactors', 'factors'));
    }

    public function update(Request $request, SubFactor $subFactor)
    {
        $request->validate([
            'general_factor_id' => 'required|exists:general_factors,id',
            'factor_id' => 'required|exists:factors,id',
            'sub_factor_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:Active,Inactive'
        ]);

        // Validate that factor belongs to the selected general factor
        $factor = Factor::find($request->factor_id);
        if ($factor->general_factor_id != $request->general_factor_id) {
            return back()->withErrors(['factor_id' => 'Selected factor does not belong to the selected general factor.']);
        }

        $subFactor->update($request->all());

        return redirect()->route('sub-factors.index')
                        ->with('success', 'Sub Factor updated successfully!');
    }

    public function destroy(SubFactor $subFactor)
    {
        $subFactor->delete();

        return redirect()->route('sub-factors.index')
                        ->with('success', 'Sub Factor deleted successfully!');
    }
}
