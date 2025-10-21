<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\AllowanceDetail;
use App\Models\OtherBenefit;
use App\Models\OtherBenefitDetail;
use Illuminate\Http\Request;

class AllowanceController extends Controller
{

    public function index()
    {
        $allowances = Allowance::all();
        return view("allowance.direct.index", compact("allowances"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'allowance_name' => 'required|string|max:255',
        ]);

        $allowance = Allowance::create($request->all());
        return redirect()->back()->with('success', 'Allowance added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'allowance_name' => 'required|string|max:255',
        ]);

        // Find the allowance by ID
        $allowance = Allowance::findOrFail($id);

        // Update the allowance's name
        $allowance->allowance_name = $request->input('allowance_name');
        // Save the updated allowance
        $allowance->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Allowance updated successfully!');
    }

    public function destroy(string $id)
    {
        $allowance = Allowance::findOrFail($id);
        $allowance->delete();
        return redirect()->back()->with('success', 'Allowance deleted successfully');
    }

    public function details()
    {
        $details = AllowanceDetail::with('allowance')->get(); // Add eager loading
        $allowances = Allowance::all();
        return view("allowance.direct.details", compact('details', 'allowances'));
    }

    public function detailsStore(Request $request)
    {
        $request->validate([
            'allowance_id' => 'required|exists:allowances,id',
            'calculation_type' => 'required|in:amount,percentage',
            'amount' => 'nullable|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'taxable' => 'required|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        // Ensure taxable is properly converted to boolean
        $data = $request->all();
        $data['taxable'] = (bool) $request->input('taxable');

        AllowanceDetail::create($data);

        return redirect()->back()->with('success', 'Detail added successfully.');
    }

    public function detailsUpdate(Request $request, $id)
    {
        $request->validate([
            'allowance_id' => 'required|exists:allowances,id',
            'calculation_type' => 'required|in:amount,percentage',
            'amount' => 'nullable|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'taxable' => 'required|boolean',
            'status' => 'required|in:active,inactive',
        ]);

        $detail = AllowanceDetail::findOrFail($id);

        // Ensure taxable is properly converted to boolean
        $data = $request->all();
        $data['taxable'] = (bool) $request->input('taxable');

        $detail->update($data);

        return redirect()->back()->with('success', 'Detail updated successfully.');
    }

    public function detailsDestroy($id)
    {
        $detail = AllowanceDetail::findOrFail($id);
        $detail->delete();

        return redirect()->back()->with('success', 'Detail deleted successfully.');
    }

    public function other_benefits(){
        $other_benefits = OtherBenefit::all();
        return view('allowance.others.index', compact('other_benefits'));
    }

    public function other_benefit_store(Request $request)
    {
        $request->validate([
            'other_benefit_name' => 'required|string|max:255',
        ]);

        OtherBenefit::create($request->all());
        return redirect()->back()->with('success', 'Other benefit added successfully');
    }

    public function other_benefit_update(Request $request, string $id)
    {
        $request->validate([
            'other_benefit_name' => 'required|string|max:255',
        ]);

        $other_benefit = OtherBenefit::findOrFail($id);
        $other_benefit->update($request->all());

        return redirect()->back()->with('success', 'Other benefit updated successfully');
    }

    public function other_benefit_destroy(string $id)
    {
        $other_benefit = OtherBenefit::findOrFail($id);
        $other_benefit->delete();
        return redirect()->back()->with('success', 'Other benefit deleted successfully');
    }

    public function other_benefit_detail(){
        $details = OtherBenefitDetail::with('otherBenefit')->get();
        $other_benefits = OtherBenefit::all();
        $employees = \App\Models\Employee::select('id','employee_name')->orderBy('employee_name')->get();
        return view('allowance.others.details', compact('details','other_benefits','employees'));
    }

    public function other_benefit_detail_store(Request $request)
    {
        $request->validate([
            'other_benefit_id' => 'required|exists:other_benefits,id',
            'amount' => 'required|numeric|min:0',
            'benefit_date' => 'required|date',
            'taxable' => 'required|boolean',
            'status' => 'required|in:active,inactive',
            'apply_to_all' => 'required|boolean',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'integer|exists:employees,id',
        ]);

        $data = $request->only(['other_benefit_id','amount','benefit_date','taxable','status','apply_to_all','employee_ids']);
        if ($data['apply_to_all']) {
            $data['employee_ids'] = null;
        }
        OtherBenefitDetail::create($data);
        return redirect()->back()->with('success','Other benefit assigned successfully');
    }

    public function other_benefit_detail_update(Request $request, string $id)
    {
        $request->validate([
            'other_benefit_id' => 'required|exists:other_benefits,id',
            'amount' => 'required|numeric|min:0',
            'benefit_date' => 'required|date',
            'taxable' => 'required|boolean',
            'status' => 'required|in:active,inactive',
            'apply_to_all' => 'required|boolean',
            'employee_ids' => 'nullable|array',
            'employee_ids.*' => 'integer|exists:employees,id',
        ]);

        $detail = OtherBenefitDetail::findOrFail($id);
        $data = $request->only(['other_benefit_id','amount','benefit_date','taxable','status','apply_to_all','employee_ids']);
        if ($data['apply_to_all']) {
            $data['employee_ids'] = null;
        }
        $detail->update($data);
        return redirect()->back()->with('success','Other benefit assignment updated');
    }

    public function other_benefit_detail_destroy(string $id)
    {
        $detail = OtherBenefitDetail::findOrFail($id);
        $detail->delete();
        return redirect()->back()->with('success','Other benefit assignment deleted');
    }
}
