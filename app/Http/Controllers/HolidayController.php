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
        $holidays = holiday::all();

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
        $holiday = holiday::create($request->all());

        return redirect()->back()->with('success','holiday added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'holiday' => 'required|string|max:255',
        ]);

        // Find the holiday by ID
        $holiday = holiday::findOrFail($id);

        // Update the holiday's name
        $holiday->holiday = $request->input('holiday');

        // Save the updated holiday
        $holiday->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'holiday updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $holiday = holiday::find($id);

        if ($holiday) {
            $holiday->delete();
            return redirect()->back()->with('success', 'holiday deleted successfully');
        } else {
            return redirect()->back()->with('error', 'holiday not found');
        }
    }
}
