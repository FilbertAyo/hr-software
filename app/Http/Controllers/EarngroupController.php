<?php

namespace App\Http\Controllers;

use App\Models\Earngroup;
use Illuminate\Http\Request;

class EarngroupController extends Controller
{
    public function index()
    {
        $earngroups = Earngroup::all();

        return view("settings.earngroup", compact("earngroups"));
    }

    public function store(Request $request)
    {
        $earngroup = Earngroup::create($request->all());

        return redirect()->back()->with('success','earngroup added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'earngroup_name' => 'required|string|max:255',
        ]);

        $earngroup = Earngroup::findOrFail($id);


        $earngroup->earngroup_name = $request->input('earngroup_name');


        $earngroup->save();
        return redirect()->back()->with('success', 'earngroup updated successfully!');
    }

    public function destroy(string $id)
    {
        $earngroup = Earngroup::find($id);

    if ($earngroup) {
        $earngroup->delete();
        return redirect()->back()->with('success', 'earngroup deleted successfully');
    } else {
        return redirect()->back()->with('error', 'earngroup not found');
    }
    }
}
