<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = payment::all();

        return view("settings.payment", compact("payments"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_name' => 'required|string|max:255',
            'payment_type' => 'required|in:Dynamic,Static',
            'rate_check' => 'nullable|boolean',
            'payment_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:Active,Inactive'
        ]);

        $payment = Payment::create([
            'payment_name' => $request->payment_name,
            'payment_type' => $request->payment_type,
            'rate_check' => $request->has('rate_check') ? 1 : 0,
            'payment_rate' => $request->payment_rate,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success','Payment added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'payment_name' => 'required|string|max:255',
            'payment_type' => 'required|in:Dynamic,Static',
            'rate_check' => 'nullable|boolean',
            'payment_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:Active,Inactive'
        ]);

        $payment = Payment::findOrFail($id);

        $payment->update([
            'payment_name' => $request->payment_name,
            'payment_type' => $request->payment_type,
            'rate_check' => $request->has('rate_check') ? 1 : 0,
            'payment_rate' => $request->payment_rate,
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::find($id);

        if ($payment) {
            $payment->delete();
            return redirect()->back()->with('success', 'Payment deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Payment not found');
        }
    }
}
