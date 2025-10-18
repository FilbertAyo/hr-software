<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{


    public function index()
    {
        $supervisors = Supervisor::all();

        return view("settings.supervisor", compact("supervisors"));
    }

    public function store(Request $request)
    {
        $supervisor = Supervisor::create($request->all());

        return redirect()->back()->with('success','supervisor added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'supervisor_name' => 'required|string|max:255',
        ]);

        $supervisor = Supervisor::findOrFail($id);


        $supervisor->supervisor_name = $request->input('supervisor_name');


        $supervisor->save();
        return redirect()->back()->with('success', 'supervisor updated successfully!');
    }

    public function destroy(string $id)
    {
        $supervisor = Supervisor::find($id);

    if ($supervisor) {
        $supervisor->delete();
        return redirect()->back()->with('success', 'supervisor deleted successfully');
    } else {
        return redirect()->back()->with('error', 'supervisor not found');
    }
    }
}
