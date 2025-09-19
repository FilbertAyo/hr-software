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
        $payment = payment::create($request->all());

        return redirect()->back()->with('success','payment added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'payment' => 'required|string|max:255',
        ]);

        // Find the payment by ID
        $payment = payment::findOrFail($id);

        // Update the payment's name
        $payment->payment = $request->input('payment');

        // Save the updated payment
        $payment->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'payment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = payment::find($id);

        if ($payment) {
            $payment->delete();
            return redirect()->back()->with('success', 'payment deleted successfully');
        } else {
            return redirect()->back()->with('error', 'payment not found');
        }
    }
}
