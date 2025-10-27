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
        $factors = Factor::with(['generalFactor'])->withCount('subFactors')->get();
        $generalFactors = GeneralFactor::all();
        return view('performance.sub-factors.index', compact('factors', 'generalFactors'));
    }

    public function create()
    {
        $generalFactors = GeneralFactor::all();
        $factors = Factor::all();
        return view('performance.sub-factors.create', compact('generalFactors', 'factors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'general_factor_id' => 'required|exists:general_factors,id',
            'factor_id' => 'required|exists:factors,id',
            'sub_factors' => 'nullable|array',
            'sub_factors.*.sub_factor_name' => 'required_with:sub_factors|string|max:255',
            'sub_factors.*.description' => 'nullable|string',
        ]);

        // Validate that factor belongs to the selected general factor
        $factor = Factor::find($request->factor_id);
        if ($factor->general_factor_id != $request->general_factor_id) {
            return back()->withErrors(['factor_id' => 'Selected factor does not belong to the selected general factor.']);
        }

        // Create nested sub-factors
        foreach ($request->input('sub_factors', []) as $sf) {
            if (!empty($sf['sub_factor_name'])) {
                SubFactor::create([
                    'factor_id' => $request->factor_id,
                    'sub_factor_name' => $sf['sub_factor_name'],
                    'description' => $sf['description'] ?? null,
                ]);
            }
        }

        return redirect()->route('sub-factors.index')
                        ->with('success', 'Sub Factors created successfully!');
    }

    public function show(SubFactor $subFactor)
    {
        $subFactor->load(['factor.generalFactor']);
        return view('performance.sub-factors.show', compact('subFactor'));
    }

    public function edit(SubFactor $subFactor)
    {
        $generalFactors = GeneralFactor::all();
        $factors = Factor::all();
        return view('performance.sub-factors.edit', compact('subFactor', 'generalFactors', 'factors'));
    }

    public function update(Request $request, SubFactor $subFactor)
    {
        $request->validate([
            'factor_id' => 'required|exists:factors,id',
            'sub_factor_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sub_factors' => 'nullable|array',
            'sub_factors.*.id' => 'nullable|integer|exists:sub_factors,id',
            'sub_factors.*.sub_factor_name' => 'required_with:sub_factors|string|max:255',
            'sub_factors.*.description' => 'nullable|string',
            'sub_factors.*._delete' => 'nullable|boolean',
        ]);

        $subFactor->update($request->only(['factor_id','sub_factor_name','description']));

        // Upsert/delete nested sub-factors if provided
        foreach ($request->input('sub_factors', []) as $sf) {
            $subFactorId = $sf['id'] ?? null;
            $toDelete = !empty($sf['_delete']);

            if ($subFactorId) {
                $existingSf = SubFactor::where('id', $subFactorId)
                                       ->where('factor_id', $request->factor_id)
                                       ->first();
                if (!$existingSf) {
                    continue;
                }
                if ($toDelete) {
                    $existingSf->delete();
                    continue;
                }
                $existingSf->update([
                    'sub_factor_name' => $sf['sub_factor_name'] ?? $existingSf->sub_factor_name,
                    'description' => $sf['description'] ?? null,
                ]);
            } else {
                if (!empty($sf['sub_factor_name'])) {
                    SubFactor::create([
                        'factor_id' => $request->factor_id,
                        'sub_factor_name' => $sf['sub_factor_name'],
                        'description' => $sf['description'] ?? null,
                    ]);
                }
            }
        }

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

