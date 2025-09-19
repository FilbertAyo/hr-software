<?php

namespace App\Http\Controllers;

use App\Models\Paygrade;
use Illuminate\Http\Request;

class PaygradeController extends Controller
{
    public function index()
    {
        $paygrades = paygrade::all();

        return view("settings.paygrade", compact("paygrades"));
    }

    public function store(Request $request)
    {
        $paygrade = paygrade::create($request->all());

        return redirect()->back()->with('success','paygrade added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'paygrade_name' => 'required|string|max:255',
        ]);

        $paygrade = paygrade::findOrFail($id);


        $paygrade->paygrade_name = $request->input('paygrade_name');


        $paygrade->save();
        return redirect()->back()->with('success', 'paygrade updated successfully!');
    }

    public function destroy(string $id)
    {
        $paygrade = paygrade::find($id);

    if ($paygrade) {
        $paygrade->delete();
        return redirect()->back()->with('success', 'paygrade deleted successfully');
    } else {
        return redirect()->back()->with('error', 'paygrade not found');
    }
    }
}
