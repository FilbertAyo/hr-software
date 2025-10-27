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
        $generalFactors = GeneralFactor::all();
        return view('performance.sub-factors.index', compact('factors', 'generalFactors'));
    }

    public function create()
    {
        $generalFactors = GeneralFactor::all();
        return view('performance.factors.create', compact('generalFactors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'general_factor_id' => 'required|exists:general_factors,id',
            'factor_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Factor::create($request->only(['general_factor_id','factor_name','description']));

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
        $generalFactors = GeneralFactor::all();
        return view('performance.factors.edit', compact('factor', 'generalFactors'));
    }

    public function update(Request $request, Factor $factor)
    {
        $request->validate([
            'sub_factors' => 'required|array',
            'sub_factors.*.id' => 'nullable|integer|exists:sub_factors,id',
            'sub_factors.*.sub_factor_name' => 'nullable|string|max:255',
            'sub_factors.*.description' => 'nullable|string',
            'sub_factors.*._delete' => 'nullable',
        ]);

        $updatedCount = 0;
        $deletedCount = 0;
        $createdCount = 0;

        // Upsert/delete nested sub-factors
        foreach ($request->input('sub_factors', []) as $sf) {
            $subFactorId = $sf['id'] ?? null;
            $toDelete = !empty($sf['_delete']);

            if ($subFactorId) {
                $existingSf = \App\Models\SubFactor::where('id', $subFactorId)
                                       ->where('factor_id', $factor->id)
                                       ->first();
                if (!$existingSf) {
                    continue;
                }
                if ($toDelete) {
                    $existingSf->delete();
                    $deletedCount++;
                    continue;
                }
                if (!empty($sf['sub_factor_name'])) {
                    $existingSf->update([
                        'sub_factor_name' => $sf['sub_factor_name'],
                        'description' => $sf['description'] ?? null,
                    ]);
                    $updatedCount++;
                }
            } else {
                if (!empty($sf['sub_factor_name'])) {
                    \App\Models\SubFactor::create([
                        'factor_id' => $factor->id,
                        'sub_factor_name' => $sf['sub_factor_name'],
                        'description' => $sf['description'] ?? null,
                    ]);
                    $createdCount++;
                }
            }
        }

        $message = 'Sub Factors updated successfully!';
        if ($createdCount > 0 || $updatedCount > 0 || $deletedCount > 0) {
            $parts = [];
            if ($createdCount > 0) $parts[] = "{$createdCount} created";
            if ($updatedCount > 0) $parts[] = "{$updatedCount} updated";
            if ($deletedCount > 0) $parts[] = "{$deletedCount} deleted";
            $message = 'Sub Factors: ' . implode(', ', $parts) . '!';
        }

        return redirect()->route('sub-factors.index')
                        ->with('success', $message);
    }

    public function destroy(Factor $factor)
    {
        $subFactorsCount = $factor->subFactors()->count();
        $factor->delete();

        $message = $subFactorsCount > 0 
            ? "Factor and {$subFactorsCount} sub-factor(s) deleted successfully!"
            : 'Factor deleted successfully!';

        return redirect()->route('sub-factors.index')
                        ->with('success', $message);
    }

    // API method for getting sub-factors by factor
    public function getSubFactors($id)
    {
        $subFactors = Factor::find($id)?->subFactors()->get();

        return response()->json($subFactors ?? []);
    }

    // API method for getting single factor details
    public function getFactor($id)
    {
        $factor = Factor::with('generalFactor')->find($id);

        return response()->json($factor ?? []);
    }
}

