<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{


    public function index()
    {
        $departments = department::all();

        return view("settings.department", compact("departments"));
    }

    public function store(Request $request)
    {
        $department = department::create($request->all());

        return redirect()->back()->with('success','department added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'department_name' => 'required|string|max:255',
        ]);

        $department = department::findOrFail($id);


        $department->department_name = $request->input('department_name');


        $department->save();
        return redirect()->back()->with('success', 'department updated successfully!');
    }

    public function destroy(string $id)
    {
        $department = department::find($id);

    if ($department) {
        $department->delete();
        return redirect()->back()->with('success', 'department deleted successfully');
    } else {
        return redirect()->back()->with('error', 'department not found');
    }
    }
}
