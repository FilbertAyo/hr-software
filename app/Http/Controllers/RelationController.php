<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use Illuminate\Http\Request;

class RelationController extends Controller
{


    public function index()
    {
        $relations = Relation::all();

        return view("settings.relation", compact("relations"));
    }

    public function store(Request $request)
    {
        $relation = relation::create($request->all());

        return redirect()->back()->with('success','relation added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'relation_type' => 'required|string|max:255',
        ]);

        $relation = Relation::findOrFail($id);


        $relation->relation_type = $request->input('relation_type');


        $relation->save();
        return redirect()->back()->with('success', 'relation updated successfully!');
    }

    public function destroy(string $id)
    {
        $relation = Relation::find($id);

    if ($relation) {
        $relation->delete();
        return redirect()->back()->with('success', 'relation deleted successfully');
    } else {
        return redirect()->back()->with('error', 'relation not found');
    }
    }

}
