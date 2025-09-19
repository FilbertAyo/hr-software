<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $banks = Bank::all();

        return view("settings.bank", compact("banks"));
    }

    public function store(Request $request)
    {
        $bank = bank::create($request->all());

        return redirect()->back()->with('success','bank added successfully');
    }


    public function update(Request $request, string $id)
    {

        $request->validate([
            'bank_name' => 'required|string|max:255',
        ]);

        $bank = bank::findOrFail($id);


        $bank->bank_name = $request->input('bank_name');


        $bank->save();
        return redirect()->back()->with('success', 'bank updated successfully!');
    }

    public function destroy(string $id)
    {
        $bank = bank::find($id);

    if ($bank) {
        $bank->delete();
        return redirect()->back()->with('success', 'bank deleted successfully');
    } else {
        return redirect()->back()->with('error', 'bank not found');
    }
    }
}
