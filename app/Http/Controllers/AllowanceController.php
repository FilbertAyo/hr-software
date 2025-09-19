<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\AllowanceDetail;
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
            'name' => 'required|string|max:255',
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
            'name' => 'required|string|max:255',
        ]);

        // Find the allowance by ID
        $allowance = Allowance::findOrFail($id);

        // Update the allowance's name
        $allowance->name = $request->input('name');

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
            'amount' => 'nullable|numeric|min:0',
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
            'amount' => 'nullable|numeric|min:0',
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
}
