<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Jobtitle;
use App\Models\Occupation;
use App\Models\Paygrade;
use Illuminate\Http\Request;

class JobtitleController extends Controller
{

    public function index()
    {
        $jobtitles = jobtitle::all();
        $categories = Occupation::all();
        $departments = Department::all();
        $paygrades = Paygrade::all();
        return view("settings.jobtitle", compact("jobtitles",'categories','departments','paygrades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'occupation_id' => 'required|exists:occupations,id',
            'pay_grade_id' => 'required|exists:paygrades,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $jobtitle = Jobtitle::create($validated);

        return redirect()->back()->with('success','jobtitle added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'job_title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'occupation_id' => 'required|exists:occupations,id',
            'pay_grade_id' => 'required|exists:paygrades,id',
            'department_id' => 'required|exists:departments,id',
        ]);

        $jobtitle = Jobtitle::findOrFail($id);

        $jobtitle->job_title = $request->input('job_title');
        $jobtitle->description = $request->input('description');
        $jobtitle->occupation_id = $request->input('occupation_id');
        $jobtitle->pay_grade_id = $request->input('pay_grade_id');
        $jobtitle->department_id = $request->input('department_id');


        $jobtitle->save();
        return redirect()->back()->with('success', 'jobtitle updated successfully!');
    }

    public function destroy(string $id)
    {
        $jobtitle = jobtitle::find($id);

    if ($jobtitle) {
        $jobtitle->delete();
        return redirect()->back()->with('success', 'jobtitle deleted successfully');
    } else {
        return redirect()->back()->with('error', 'jobtitle not found');
    }
    }
}
