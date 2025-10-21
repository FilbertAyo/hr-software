<?php

namespace App\Http\Controllers;

use App\Models\GroupBenefit;
use App\Models\Earngroup;
use App\Models\Allowance;
use Illuminate\Http\Request;

class GroupBenefitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groupBenefits = GroupBenefit::with(['earngroup', 'allowance.allowanceDetails'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('allowance.group-benefits.index', compact('groupBenefits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $earngroups = Earngroup::all();
        $allowances = Allowance::with('allowanceDetails')->get();

        return view('allowance.group-benefits.create', compact('earngroups', 'allowances'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'earngroup_ids' => 'required|array|min:1',
            'earngroup_ids.*' => 'exists:earngroups,id',
            'allowance_ids' => 'required|array|min:1',
            'allowance_ids.*' => 'exists:allowances,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Create group benefits for each combination
        foreach ($request->earngroup_ids as $earngroupId) {
            foreach ($request->allowance_ids as $allowanceId) {
                // Check if combination already exists
                $existing = GroupBenefit::where('earngroup_id', $earngroupId)
                    ->where('allowance_id', $allowanceId)
                    ->first();

                if (!$existing) {
                    GroupBenefit::create([
                        'earngroup_id' => $earngroupId,
                        'allowance_id' => $allowanceId,
                        'status' => $request->status,
                    ]);
                }
            }
        }

        return redirect()->route('group-benefits.index')
            ->with('success', 'Group benefits created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupBenefit $groupBenefit)
    {
        $groupBenefit->load(['earngroup', 'allowance.allowanceDetails']);
        return view('allowance.group-benefits.show', compact('groupBenefit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupBenefit $groupBenefit)
    {
        $earngroups = Earngroup::all();
        $allowances = Allowance::with('allowanceDetails')->get();

        return view('allowance.group-benefits.edit', compact('groupBenefit', 'earngroups', 'allowances'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupBenefit $groupBenefit)
    {
        $request->validate([
            'earngroup_id' => 'required|exists:earngroups,id',
            'allowance_id' => 'required|exists:allowances,id',
            'status' => 'required|in:active,inactive',
        ]);

        // Check if combination already exists (excluding current record)
        $existing = GroupBenefit::where('earngroup_id', $request->earngroup_id)
            ->where('allowance_id', $request->allowance_id)
            ->where('id', '!=', $groupBenefit->id)
            ->first();

        if ($existing) {
            return back()->withErrors(['combination' => 'This earning group and benefit combination already exists.']);
        }

        $groupBenefit->update($request->all());

        return redirect()->route('group-benefits.index')
            ->with('success', 'Group benefit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupBenefit $groupBenefit)
    {
        $groupBenefit->delete();

        return redirect()->route('group-benefits.index')
            ->with('success', 'Group benefit deleted successfully.');
    }
}
