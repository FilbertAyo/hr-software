<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationController extends Controller
{


    public function index()
    {
        $occupations = occupation::all();

        return view("settings.occupation", compact("occupations"));
    }

    public function store(Request $request)
    {
        $occupation = occupation::create($request->all());

        return redirect()->back()->with('success','occupation added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'job_category' => 'required|string|max:255',
        ]);

        $occupation = occupation::findOrFail($id);


        $occupation->job_category = $request->input('job_category');


        $occupation->save();
        return redirect()->back()->with('success', 'occupation updated successfully!');
    }

    public function destroy(string $id)
    {
        $occupation = occupation::find($id);

    if ($occupation) {
        $occupation->delete();
        return redirect()->back()->with('success', 'occupation deleted successfully');
    } else {
        return redirect()->back()->with('error', 'occupation not found');
    }
    }

}
