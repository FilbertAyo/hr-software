<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::all();

        return view("settings.holiday", compact("holidays"));
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
        $validatedData = $request->validate([
            'holiday_name' => 'required|string|max:255',
            'holiday_date' => 'required|date',
            'is_recurring' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $validatedData['is_recurring'] = $request->has('is_recurring') ? (bool)$request->is_recurring : false;

        Holiday::create($validatedData);

        return redirect()->back()->with('success','Holiday added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'holiday_name' => 'required|string|max:255',
            'holiday_date' => 'required|date',
            'is_recurring' => 'boolean',
            'description' => 'nullable|string',
        ]);

        // Find the holiday by ID
        $holiday = Holiday::findOrFail($id);

        // Update the holiday data
        $holiday->update($validatedData);

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Holiday updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $holiday = Holiday::find($id);

        if ($holiday) {
            $holiday->delete();
            return redirect()->back()->with('success', 'holiday deleted successfully');
        } else {
            return redirect()->back()->with('error', 'holiday not found');
        }
    }
}
