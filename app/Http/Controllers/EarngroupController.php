<?php

namespace App\Http\Controllers;

use App\Models\Earngroup;
use Illuminate\Http\Request;

class EarngroupController extends Controller
{
    public function index()
    {
        $earngroups = earngroup::all();

        return view("settings.earngroup", compact("earngroups"));
    }

    public function store(Request $request)
    {
        $earngroup = earngroup::create($request->all());

        return redirect()->back()->with('success','earngroup added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'earn_group' => 'required|string|max:255',
        ]);

        $earngroup = earngroup::findOrFail($id);


        $earngroup->earn_group = $request->input('earn_group');


        $earngroup->save();
        return redirect()->back()->with('success', 'earngroup updated successfully!');
    }

    public function destroy(string $id)
    {
        $earngroup = earngroup::find($id);

    if ($earngroup) {
        $earngroup->delete();
        return redirect()->back()->with('success', 'earngroup deleted successfully');
    } else {
        return redirect()->back()->with('error', 'earngroup not found');
    }
    }
}
