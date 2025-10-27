<?php

namespace App\Http\Controllers;

use App\Models\GeneralFactor;
use App\Models\Factor;
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
            'factor_name' => 'required|string|max:255|unique:general_factors,factor_name',
            'description' => 'nullable|string',
            'factors' => 'nullable|array',
            'factors.*.factor_name' => 'required_with:factors|string|max:255',
            'factors.*.description' => 'nullable|string',
        ]);

        $generalFactor = GeneralFactor::create($request->only(['factor_name','description']));

        // Create nested factors if provided
        foreach ($request->input('factors', []) as $f) {
            if (!empty($f['factor_name'])) {
                Factor::create([
                    'general_factor_id' => $generalFactor->id,
                    'factor_name' => $f['factor_name'],
                    'description' => $f['description'] ?? null,
                ]);
            }
        }

        return redirect()->route('general-factors.index')
                        ->with('success', 'General Factor created successfully!');
    }

    public function show(GeneralFactor $generalFactor)
    {
        $generalFactor->load(['factors']);
        return view('performance.general-factors.show', compact('generalFactor'));
    }

    public function edit(GeneralFactor $generalFactor)
    {
        return view('performance.general-factors.edit', compact('generalFactor'));
    }

    public function update(Request $request, GeneralFactor $generalFactor)
    {
        $request->validate([
            'factor_name' => 'required|string|max:255|unique:general_factors,factor_name,' . $generalFactor->id,
            'description' => 'nullable|string',
            'factors' => 'nullable|array',
            'factors.*.id' => 'nullable|integer|exists:factors,id',
            'factors.*.factor_name' => 'required_with:factors|string|max:255',
            'factors.*.description' => 'nullable|string',
            'factors.*._delete' => 'nullable|boolean',
        ]);

        $generalFactor->update($request->only(['factor_name','description']));

        // Upsert/delete nested factors
        foreach ($request->input('factors', []) as $f) {
            $factorId = $f['id'] ?? null;
            $toDelete = !empty($f['_delete']);

            if ($factorId) {
                $factor = Factor::where('id', $factorId)
                                ->where('general_factor_id', $generalFactor->id)
                                ->first();
                if (!$factor) {
                    continue;
                }
                if ($toDelete) {
                    $factor->delete();
                    continue;
                }
                $factor->update([
                    'factor_name' => $f['factor_name'] ?? $factor->factor_name,
                    'description' => $f['description'] ?? null,
                ]);
            } else {
                if (!empty($f['factor_name'])) {
                    Factor::create([
                        'general_factor_id' => $generalFactor->id,
                        'factor_name' => $f['factor_name'],
                        'description' => $f['description'] ?? null,
                    ]);
                }
            }
        }

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
        $factors = GeneralFactor::find($id)?->factors()->get();

        return response()->json($factors ?? []);
    }
}

