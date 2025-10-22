<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanInstallment;
use App\Models\LoanType;
use App\Models\LoanRestructure;
use App\Models\PayrollPeriod;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = session('selected_company_id');

        $loans = Loan::where('company_id', $companyId)
            ->with(['employee', 'loanType', 'payrollPeriod'])
            ->orderBy('created_at', 'desc')
            ->get();

        $employees = Employee::where('company_id', $companyId)->get();
        $loanTypes = LoanType::all();

        // Get current payroll period from session (set by middleware)
        $currentPayrollPeriod = session('current_payroll_period');

        // Get upcoming payroll periods
        $upcomingPayrollPeriods = PayrollPeriod::where('company_id', $companyId)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        return view("loans.loan.index", compact('loans', 'employees', 'loanTypes', 'currentPayrollPeriod', 'upcomingPayrollPeriods'));
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
            'payroll_period_id' => 'nullable|exists:payroll_periods,id',
            'notes' => 'nullable|string',
        ]);

        $companyId = session('selected_company_id');

        // Create the loan with pending status
        $loan = Loan::create([
            'company_id' => $companyId,
            'payroll_period_id' => $request->payroll_period_id,
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
        $loan = Loan::with(['employee', 'loanType', 'installments', 'payrollPeriod'])->findOrFail($id);

        // Check authorization - ensure loan belongs to current company
        $companyId = session('selected_company_id');
        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        // Get current payroll period from session (set by middleware)
        $currentPayrollPeriod = session('current_payroll_period');

        return view('loans.loan.show', compact('loan', 'currentPayrollPeriod'));
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
        $companyId = session('selected_company_id');
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
            'original_installment_count' => $installmentCount,
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
     * Show custom installment editor
     */
    public function showCustomInstallments(Loan $loan)
    {
        $companyId = session('selected_company_id');

        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        // Can only create custom installments for pending loans without installments
        if ($loan->status != 'pending') {
            return redirect()->route('loan.show', $loan->id)
                ->with('error', 'Custom installments can only be set for pending loans.');
        }

        if ($loan->installments()->count() > 0) {
            return redirect()->route('loan.show', $loan->id)
                ->with('error', 'This loan already has installments set up.');
        }

        // Get current payroll period from session (set by middleware)
        $currentPayrollPeriod = session('current_payroll_period');

        return view('loans.loan.custom-installments', compact('loan', 'currentPayrollPeriod'));
    }

    /**
     * Store custom installments
     */
    public function storeCustomInstallments(Request $request, Loan $loan)
    {
        $request->validate([
            'installments' => 'required|array|min:1',
            'installments.*.amount' => 'required|numeric|min:0',
            'installments.*.due_date' => 'required|date',
        ]);

        $companyId = session('selected_company_id');

        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        if ($loan->status != 'pending') {
            return redirect()->back()->with('error', 'Custom installments can only be set for pending loans.');
        }

        if ($loan->installments()->count() > 0) {
            return redirect()->back()->with('error', 'This loan already has installments.');
        }

        // Calculate total of installments
        $totalInstallments = collect($request->installments)
            ->sum(function ($installment) {
                return floatval($installment['amount']);
            });

        // Validate that total matches remaining amount
        if (abs($totalInstallments - $loan->remaining_amount) > 0.01) { // Allow 1 cent difference for rounding
            return redirect()->back()
                ->withInput()
                ->with('error', sprintf(
                    'Total installments (%.2f) must equal the loan amount (%.2f). Difference: %.2f',
                    $totalInstallments,
                    $loan->remaining_amount,
                    $totalInstallments - $loan->remaining_amount
                ));
        }

        // Filter out empty installments (amount = 0)
        $validInstallments = collect($request->installments)
            ->filter(function ($installment) {
                return floatval($installment['amount']) > 0;
            })
            ->values();

        if ($validInstallments->count() == 0) {
            return redirect()->back()->with('error', 'At least one installment must have an amount greater than 0.');
        }

        DB::beginTransaction();
        try {
            // Sort installments by date
            $sortedInstallments = $validInstallments->sortBy('due_date')->values();

            // Create installments
            foreach ($sortedInstallments as $index => $installmentData) {
                LoanInstallment::create([
                    'loan_id' => $loan->id,
                    'installment_number' => $index + 1,
                    'amount' => $installmentData['amount'],
                    'due_date' => Carbon::parse($installmentData['due_date'])->format('Y-m-d'),
                    'status' => 'pending',
                ]);
            }

            // Calculate start and end dates
            $startDate = Carbon::parse($sortedInstallments->first()['due_date']);
            $endDate = Carbon::parse($sortedInstallments->last()['due_date']);

            // Calculate average monthly payment
            $avgMonthlyPayment = $totalInstallments / $sortedInstallments->count();

            // Update loan details
            $loan->update([
                'installment_count' => $sortedInstallments->count(),
                'original_installment_count' => $sortedInstallments->count(),
                'monthly_payment' => round($avgMonthlyPayment, 2),
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'status' => 'active',
            ]);

            DB::commit();

            return redirect()->route('loan.show', $loan->id)
                ->with('success', 'Custom installments created successfully. Loan is now active.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create installments: ' . $e->getMessage());
        }
    }

    /**
     * Edit existing installments (for active loans)
     */
    public function editInstallments(Loan $loan)
    {
        $companyId = session('selected_company_id');

        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        // Can edit installments for active or pending loans
        if (!in_array($loan->status, ['active', 'pending'])) {
            return redirect()->route('loan.show', $loan->id)
                ->with('error', 'Can only edit installments for active or pending loans.');
        }

        if ($loan->installments()->count() == 0) {
            return redirect()->route('loan.show', $loan->id)
                ->with('error', 'This loan has no installments to edit.');
        }

        $installments = $loan->installments()->orderBy('due_date', 'asc')->get();

        // Separate paid and pending installments
        $paidInstallments = $installments->where('status', 'paid');
        $pendingInstallments = $installments->where('status', '!=', 'paid');

        return view('loans.loan.edit-installments', compact('loan', 'paidInstallments', 'pendingInstallments'));
    }

    /**
     * Update installments (modify pending ones only)
     */
    public function updateInstallments(Request $request, Loan $loan)
    {
        $request->validate([
            'installments' => 'required|array|min:1',
            'installments.*.id' => 'required', // Don't validate exists - we handle "new" separately
            'installments.*.amount' => 'required|numeric|min:0',
            'installments.*.due_date' => 'required|date',
        ]);

        $companyId = session('selected_company_id');

        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        if (!in_array($loan->status, ['active', 'pending'])) {
            return redirect()->back()->with('error', 'Can only edit installments for active or pending loans.');
        }

        // Calculate total amount already paid
        $paidAmount = $loan->installments()->where('status', 'paid')->sum('amount');

        // Calculate total of new pending installments
        $totalPendingAmount = collect($request->installments)
            ->sum(function ($installment) {
                return floatval($installment['amount']);
            });

        // Validate that paid + pending = original loan amount
        $expectedTotal = $loan->loan_amount;
        $newTotal = $paidAmount + $totalPendingAmount;

        if (abs($newTotal - $expectedTotal) > 0.01) {
            return redirect()->back()
                ->withInput()
                ->with('error', sprintf(
                    'Total (paid + pending) must equal loan amount. Paid: %.2f, Pending: %.2f, Total: %.2f, Required: %.2f',
                    $paidAmount,
                    $totalPendingAmount,
                    $newTotal,
                    $expectedTotal
                ));
        }

        DB::beginTransaction();
        try {
            // Create snapshot of old installments for history
            $oldInstallments = $loan->installments->map(function ($inst) {
                return [
                    'installment_number' => $inst->installment_number,
                    'amount' => $inst->amount,
                    'due_date' => $inst->due_date,
                    'status' => $inst->status,
                    'paid_date' => $inst->paid_date,
                ];
            })->toArray();

            // Delete all pending installments (they will be recreated)
            $loan->installments()->where('status', 'pending')->delete();

            // Create/recreate installments from the request
            $installmentNumber = 1;
            foreach ($request->installments as $installmentData) {
                // Skip if amount is 0 or empty
                if (empty($installmentData['amount']) || floatval($installmentData['amount']) <= 0) {
                    continue;
                }

                LoanInstallment::create([
                    'loan_id' => $loan->id,
                    'installment_number' => $installmentNumber,
                    'amount' => $installmentData['amount'],
                    'due_date' => Carbon::parse($installmentData['due_date'])->format('Y-m-d'),
                    'status' => 'pending',
                ]);

                $installmentNumber++;
            }

            // Recalculate loan details
            $allInstallments = $loan->installments()->orderBy('due_date', 'asc')->get();
            $startDate = $allInstallments->first()->due_date;
            $endDate = $allInstallments->last()->due_date;
            $avgMonthlyPayment = $totalPendingAmount / count($request->installments);

            // Update remaining amount (loan amount - paid amount)
            $remainingAmount = $loan->loan_amount - $paidAmount;

            // Create restructure record if this is an active loan
            if ($loan->status == 'active') {
                LoanRestructure::create([
                    'loan_id' => $loan->id,
                    'restructured_by' => Auth::id(),
                    'old_installment_count' => $loan->installment_count,
                    'new_installment_count' => $allInstallments->count(),
                    'old_monthly_payment' => $loan->monthly_payment,
                    'new_monthly_payment' => round($avgMonthlyPayment, 2),
                    'old_start_date' => $loan->start_date,
                    'new_start_date' => $startDate,
                    'old_end_date' => $loan->end_date,
                    'new_end_date' => $endDate,
                    'remaining_amount_at_restructure' => $remainingAmount,
                    'reason' => 'Manual installment amounts modified',
                    'old_installments_snapshot' => $oldInstallments,
                ]);

                $loan->update([
                    'is_restructured' => true,
                    'restructure_count' => $loan->restructure_count + 1,
                ]);
            }

            // Update loan
            $loan->update([
                'remaining_amount' => $remainingAmount,
                'monthly_payment' => round($avgMonthlyPayment, 2),
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]);

            DB::commit();

            return redirect()->route('loan.show', $loan->id)
                ->with('success', 'Installments updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update installments: ' . $e->getMessage());
        }
    }

    /**
     * Get employee's remaining loan amount (AJAX)
     */
    public function getEmployeeRemainingLoan($employeeId)
    {
        $companyId = session('selected_company_id');

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

    /**
     * Show loan restructure form
     */
    public function showRestructure(Loan $loan)
    {
        $companyId = session('selected_company_id');

        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        // Can only restructure active loans
        if ($loan->status != 'active') {
            return redirect()->route('loan.show', $loan->id)
                ->with('error', 'Only active loans can be restructured.');
        }

        // Get current payroll period from session (set by middleware)
        $currentPayrollPeriod = session('current_payroll_period');

        // Get upcoming payroll periods for start date selection
        $upcomingPayrollPeriods = PayrollPeriod::where('company_id', $companyId)
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        return view('loans.loan.restructure', compact('loan', 'currentPayrollPeriod', 'upcomingPayrollPeriods'));
    }

    /**
     * Process loan restructure
     */
    public function processRestructure(Request $request, Loan $loan)
    {
        $request->validate([
            'new_installment_count' => 'required|integer|min:1|max:60',
            'new_start_month' => 'required|date',
            'reason' => 'required|string|max:1000',
        ]);

        $companyId = session('selected_company_id');

        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        if ($loan->status != 'active') {
            return redirect()->back()->with('error', 'Only active loans can be restructured.');
        }

        DB::beginTransaction();
        try {
            // Create snapshot of current installments
            $oldInstallments = $loan->installments->map(function ($inst) {
                return [
                    'installment_number' => $inst->installment_number,
                    'amount' => $inst->amount,
                    'due_date' => $inst->due_date,
                    'status' => $inst->status,
                    'paid_date' => $inst->paid_date,
                ];
            })->toArray();

            // Create restructure record
            LoanRestructure::create([
                'loan_id' => $loan->id,
                'restructured_by' => Auth::id(),
                'old_installment_count' => $loan->installment_count,
                'new_installment_count' => $request->new_installment_count,
                'old_monthly_payment' => $loan->monthly_payment,
                'new_monthly_payment' => round($loan->remaining_amount / $request->new_installment_count, 2),
                'old_start_date' => $loan->start_date,
                'new_start_date' => Carbon::parse($request->new_start_month),
                'old_end_date' => $loan->end_date,
                'new_end_date' => Carbon::parse($request->new_start_month)->addMonths($request->new_installment_count - 1),
                'remaining_amount_at_restructure' => $loan->remaining_amount,
                'reason' => $request->reason,
                'old_installments_snapshot' => $oldInstallments,
            ]);

            // Delete old unpaid installments
            $loan->installments()->where('status', 'pending')->delete();

            // Calculate new installment amount
            $newAmountPerInstallment = round($loan->remaining_amount / $request->new_installment_count, 2);
            $newStartDate = Carbon::parse($request->new_start_month);

            // Create new installments
            for ($i = 0; $i < $request->new_installment_count; $i++) {
                $dueDate = $newStartDate->copy()->addMonths($i);

                LoanInstallment::create([
                    'loan_id' => $loan->id,
                    'installment_number' => $i + 1,
                    'amount' => $newAmountPerInstallment,
                    'due_date' => $dueDate->format('Y-m-d'),
                    'status' => 'pending',
                ]);
            }

            // Update loan
            $loan->update([
                'installment_count' => $request->new_installment_count,
                'monthly_payment' => $newAmountPerInstallment,
                'start_date' => $newStartDate->format('Y-m-d'),
                'end_date' => $newStartDate->copy()->addMonths($request->new_installment_count - 1)->format('Y-m-d'),
                'is_restructured' => true,
                'restructure_count' => $loan->restructure_count + 1,
            ]);

            DB::commit();

            return redirect()->route('loan.show', $loan->id)
                ->with('success', 'Loan has been successfully restructured.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to restructure loan: ' . $e->getMessage());
        }
    }

    /**
     * Show loan history (restructures)
     */
    public function showHistory(Loan $loan)
    {
        $companyId = session('selected_company_id');

        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        $restructures = $loan->restructures()->with('restructuredBy')->orderBy('created_at', 'desc')->get();

        return view('loans.loan.history', compact('loan', 'restructures'));
    }

    /**
     * Approve loan
     */
    public function approve(Loan $loan)
    {
        $companyId = session('selected_company_id');

        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        $loan->update([
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejected_at' => null,
            'rejected_by' => null,
            'rejection_reason' => null,
        ]);

        return redirect()->back()->with('success', 'Loan approved successfully.');
    }

    /**
     * Reject loan
     */
    public function reject(Request $request, Loan $loan)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $companyId = session('selected_company_id');

        if ($loan->company_id != $companyId) {
            abort(403, 'Unauthorized access to this loan.');
        }

        $loan->update([
            'rejected_at' => now(),
            'rejected_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
            'approved_at' => null,
            'approved_by' => null,
            'status' => 'rejected',
        ]);

        return redirect()->back()->with('success', 'Loan rejected.');
    }

    /**
     * Show loan management/approval view
     */
    public function manage()
    {
        $companyId = session('selected_company_id');

        $pendingLoans = Loan::where('company_id', $companyId)
            ->where('status', 'pending')
            ->whereNull('approved_at')
            ->whereNull('rejected_at')
            ->with(['employee', 'loanType'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current payroll period
        $currentPayrollPeriod = session('current_payroll_period');

        // Get active loans
        $activeLoansQuery = Loan::where('company_id', $companyId)
            ->where('status', 'active')
            ->with(['employee', 'loanType', 'installments', 'payrollPeriod']);

        // If there's a current payroll period, also include completed loans that had installments due in this period
        if ($currentPayrollPeriod) {
            $completedInPeriodLoans = Loan::where('company_id', $companyId)
                ->where('status', 'completed')
                ->whereHas('installments', function ($query) use ($currentPayrollPeriod) {
                    $query->where('status', 'paid')
                        ->whereBetween('due_date', [
                            $currentPayrollPeriod->start_date,
                            $currentPayrollPeriod->end_date
                        ]);
                })
                ->with(['employee', 'loanType', 'installments', 'payrollPeriod']);

            // Combine active loans with completed loans from current period
            $activeLoans = $activeLoansQuery->get()->merge($completedInPeriodLoans->get())
                ->sortByDesc('created_at');
        } else {
            $activeLoans = $activeLoansQuery->orderBy('created_at', 'desc')->get();
        }

        $rejectedLoans = Loan::where('company_id', $companyId)
            ->whereNotNull('rejected_at')
            ->with(['employee', 'loanType', 'rejectedBy'])
            ->orderBy('rejected_at', 'desc')
            ->get();

        return view('loans.loan.manage', compact('pendingLoans', 'activeLoans', 'rejectedLoans', 'currentPayrollPeriod'));
    }
}
