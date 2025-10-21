<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\LoanType;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = session('selected_company');

        $loans = Loan::where('company_id', $companyId)
            ->with(['employee', 'loanType'])
            ->orderBy('created_at', 'desc')
            ->get();

        $employees = Employee::where('company_id', $companyId)->get();
        $loanTypes = LoanType::all();

        return view("loans.loan.index", compact('loans', 'employees', 'loanTypes'));
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
            'employee_id' => 'required|exists:employees,id',
            'loan_type_id' => 'required|exists:loan_types,id',
            'loan_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $companyId = session('selected_company');

        // Create the loan with pending status
        $loan = Loan::create([
            'company_id' => $companyId,
            'employee_id' => $request->employee_id,
            'loan_type_id' => $request->loan_type_id,
            'loan_amount' => $request->loan_amount,
            'remaining_amount' => $request->loan_amount, // Initially same as loan amount
            'notes' => $request->notes,
            'status' => 'pending', // Pending until installments are set up
        ]);

        return redirect()->route('loan.show', $loan->id)
            ->with('success', 'Loan created successfully. Please setup installments.');
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
        $loan = Loan::with(['employee', 'loanType', 'installments'])->findOrFail($id);

        // Check authorization - ensure loan belongs to current company
        $companyId = session('selected_company');
        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        return view('loans.loan.show', compact('loan'));
    }

    public function storeInstallments(Request $request, Loan $loan)
    {
        $request->validate([
            'installment_count' => 'required|integer|min:1|max:60',
            'start_month' => 'required|date',
        ]);

        // Check if loan already has installments
        if ($loan->installments()->count() > 0) {
            return redirect()->back()->with('error', 'Installments already exist for this loan.');
        }

        // Check authorization
        $companyId = session('selected_company');
        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        $installmentCount = $request->installment_count;
        $amountPerInstallment = round($loan->remaining_amount / $installmentCount, 2);
        $startDate = Carbon::parse($request->start_month);

        // Create installments
        for ($i = 0; $i < $installmentCount; $i++) {
            $dueDate = $startDate->copy()->addMonths($i);

            LoanInstallment::create([
                'loan_id' => $loan->id,
                'installment_number' => $i + 1,
                'amount' => $amountPerInstallment,
                'due_date' => $dueDate->format('Y-m-d'),
                'status' => 'pending',
            ]);
        }

        // Update loan details
        $loan->update([
            'installment_count' => $installmentCount,
            'monthly_payment' => $amountPerInstallment,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $startDate->copy()->addMonths($installmentCount - 1)->format('Y-m-d'),
            'status' => 'active', // Change status to active
        ]);

        return redirect()->route('loan.show', $loan->id)
            ->with('success', 'Installments created successfully. Loan is now active.');
    }
    public function edit(Loan $loan)
    {
        //
    }

    /**
     * Get employee's remaining loan amount (AJAX)
     */
    public function getEmployeeRemainingLoan($employeeId)
    {
        $companyId = session('selected_company');

        // Find any active loans for this employee
        $activeLoan = Loan::where('employee_id', $employeeId)
            ->where('company_id', $companyId)
            ->whereIn('status', ['active', 'pending'])
            ->orderBy('created_at', 'desc')
            ->first();

        if ($activeLoan) {
            return response()->json([
                'has_loan' => true,
                'remaining_amount' => $activeLoan->remaining_amount,
                'loan_id' => $activeLoan->id,
            ]);
        }

        return response()->json([
            'has_loan' => false,
            'remaining_amount' => 0,
        ]);
    }
}
