<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\LoanType;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
  /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = loan::all();
        $employees = Employee::all();
        $loanTypes = LoanType::all();

        return view("loans.loan.index", compact('loans','employees','loanTypes'));
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
        $loan = loan::create($request->all());

        return redirect()->back()->with('success','loan added successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  string $id)
    {
        // Validate the request data
        $request->validate([
            'loan' => 'required|string|max:255',
        ]);

        // Find the loan by ID
        $loan = loan::findOrFail($id);

        // Update the loan's name
        $loan->loan = $request->input('loan');

        // Save the updated loan
        $loan->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'loan updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $loan = loan::find($id);

        if ($loan) {
            $loan->delete();
            return redirect()->back()->with('success', 'loan deleted successfully');
        } else {
            return redirect()->back()->with('error', 'loan not found');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $loan = loan::findOrFail($id);
        return view('loans.loan.show', compact('loan'));
    }

    public function storeInstallments(Request $request, Loan $loan)
    {
        $request->validate([
            'installments_number' => 'required|integer|min:1',
            'due_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        $num = $request->installments_number;
        $amountPerInstallment = round($loan->loan_amount / $num, 2);

        // Create installments
        for ($i = 1; $i <= $num; $i++) {
            LoanInstallment::create([
                'loan_id' => $loan->id,
                'installment_number' => $i,
                'amount' => $amountPerInstallment,
                'due_date' => \Carbon\Carbon::parse($request->due_date)->addMonths($i - 1),
                'status' => 'pending',
                'remarks' => $request->remarks,
            ]);
        }

        // Remaining amount updated automatically
        $loan->remaining_amount = $loan->loan_amount;
        $loan->save();

        return redirect()->route('loan.show', $loan->id)->with('success', 'Installments created successfully.');
    }
    public function edit(Loan $loan)
    {
        //
    }


}
