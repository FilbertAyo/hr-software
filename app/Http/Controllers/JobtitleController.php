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
        $jobtitle = jobtitle::create($request->all());

        return redirect()->back()->with('success','jobtitle added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $jobtitle = jobtitle::findOrFail($id);


        $jobtitle->title = $request->input('title');


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
