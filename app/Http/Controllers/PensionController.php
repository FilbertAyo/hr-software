<?php

namespace App\Http\Controllers;

use App\Models\Pension;
use Illuminate\Http\Request;

class PensionController extends Controller
{
      /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pensions = pension::all();

        return view("settings.pension", compact("pensions"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pension = pension::create($request->all());

        return redirect()->back()->with('success','pension added successfully');
    }

    public function update(Request $request, string $id)
    {
        // Validate the request data
        $request->validate([
            'name'             => 'required|string|max:255',
            'employer_percent' => 'required|numeric',
            'employee_percent' => 'required|numeric',
            'status'           => 'required|in:active,inactive',
        ]);

        // Find the pension by ID
        $pension = Pension::findOrFail($id);

        // Update the pension fields
        $pension->update($request->only(['name','employer_percent','employee_percent','status']));


        // Save the updated pension
        $pension->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Pension updated successfully!');
    }

    public function destroy(string $id)
    {
        $pension = pension::find($id);

        if ($pension) {
            $pension->delete();
            return redirect()->back()->with('success', 'pension deleted successfully');
        } else {
            return redirect()->back()->with('error', 'pension not found');
        }
    }
}
