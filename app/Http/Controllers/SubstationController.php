<?php

namespace App\Http\Controllers;

use App\Models\Mainstation;
use App\Models\Substation;
use Illuminate\Http\Request;

class SubstationController extends Controller
{

    public function index()
    {
        $substations = Substation::with('mainstation')->get();

        $mainstations = Mainstation::all();

        return view('company.substation', compact('substations', 'mainstations'));
    }


    public function store(Request $request)
    {
        $substation = substation::create($request->all());

        return redirect()->back()->with('success','substation added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'substation_name' => 'required|string|max:255',
        ]);

        $substation = substation::findOrFail($id);


        $substation->substation_name = $request->input('substation_name');


        $substation->save();
        return redirect()->back()->with('success', 'substation updated successfully!');
    }

    public function destroy(string $id)
    {
        $substation = substation::find($id);

    if ($substation) {
        $substation->delete();
        return redirect()->back()->with('success', 'substation deleted successfully');
    } else {
        return redirect()->back()->with('error', 'substation not found');
    }
    }
}
