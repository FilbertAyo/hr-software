<?php

namespace App\Http\Controllers;

use App\Models\Mainstation;
use Illuminate\Http\Request;

class MainstationController extends Controller
{

    public function index()
    {
        $mainstations = Mainstation::withCount('substations')->get();

        return view("company.mainstation", compact("mainstations"));
    }

    public function store(Request $request)
    {
        $mainstation = mainstation::create($request->all());

        return redirect()->back()->with('success','mainstation added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'station_name' => 'required|string|max:255',
        ]);

        $mainstation = mainstation::findOrFail($id);


        $mainstation->station_name = $request->input('station_name');


        $mainstation->save();
        return redirect()->back()->with('success', 'mainstation updated successfully!');
    }

    public function destroy(string $id)
    {
        $mainstation = mainstation::find($id);

    if ($mainstation) {
        $mainstation->delete();
        return redirect()->back()->with('success', 'mainstation deleted successfully');
    } else {
        return redirect()->back()->with('error', 'mainstation not found');
    }
    }
}
