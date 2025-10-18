<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationController extends Controller
{


    public function index()
    {
        $occupations = Occupation::all();

        return view("settings.occupation", compact("occupations"));
    }

    public function store(Request $request)
    {
        $occupation = Occupation::create($request->all());

        return redirect()->back()->with('success','occupation added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'occupation_name' => 'required|string|max:255',
        ]);

        $occupation = Occupation::findOrFail($id);


        $occupation->occupation_name = $request->input('occupation_name');


        $occupation->save();
        return redirect()->back()->with('success', 'occupation updated successfully!');
    }

    public function destroy(string $id)
    {
        $occupation = Occupation::find($id);

    if ($occupation) {
        $occupation->delete();
        return redirect()->back()->with('success', 'occupation deleted successfully');
    } else {
        return redirect()->back()->with('error', 'occupation not found');
    }
    }

}
