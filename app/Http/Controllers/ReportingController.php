<?php

namespace App\Http\Controllers;

use App\Models\Reporting;
use Illuminate\Http\Request;

class ReportingController extends Controller
{
    public function index()
    {
        $reportings = reporting::all();

        return view("settings.reporting", compact("reportings"));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reporting_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $reporting = reporting::create([
            'reporting_name' => $request->input('reporting_name'),
            'description' => $request->input('description'),
        ]);

        return redirect()->back()->with('success','reporting added successfully');
    }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'reporting_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $reporting = reporting::findOrFail($id);

        $reporting->reporting_name = $request->input('reporting_name');
        $reporting->description = $request->input('description');

        $reporting->save();
        return redirect()->back()->with('success', 'reporting updated successfully!');
    }

    public function destroy(string $id)
    {
        $reporting = reporting::find($id);

    if ($reporting) {
        $reporting->delete();
        return redirect()->back()->with('success', 'reporting deleted successfully');
    } else {
        return redirect()->back()->with('error', 'reporting not found');
    }
    }
}
