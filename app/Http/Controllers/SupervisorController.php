<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use Illuminate\Http\Request;

class SupervisorController extends Controller
{


    public function index()
    {
        $supervisors = supervisor::all();

        return view("settings.supervisor", compact("supervisors"));
    }

    public function store(Request $request)
    {
        $supervisor = supervisor::create($request->all());

        return redirect()->back()->with('success','supervisor added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'supervisor' => 'required|string|max:255',
        ]);

        $supervisor = supervisor::findOrFail($id);


        $supervisor->supervisor = $request->input('supervisor');


        $supervisor->save();
        return redirect()->back()->with('success', 'supervisor updated successfully!');
    }

    public function destroy(string $id)
    {
        $supervisor = supervisor::find($id);

    if ($supervisor) {
        $supervisor->delete();
        return redirect()->back()->with('success', 'supervisor deleted successfully');
    } else {
        return redirect()->back()->with('error', 'supervisor not found');
    }
    }
}
