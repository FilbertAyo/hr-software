<?php

namespace App\Http\Controllers;

use App\Models\PayPeriod;
use App\Models\PayrollPeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PayPeriodController extends Controller
{

    public function index(Request $request)
    {
        $month = (int) $request->get('month', now()->month);
        $year  = (int) $request->get('year', now()->year);

        $payperiods = PayrollPeriod::all();

        return view('payroll.payperiod', compact('payperiods', 'month', 'year', 'payperiods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year'  => 'required|integer|min:2000',
        ]);

        $month = $validated['month'];
        $year  = $validated['year'];

        $periodName = Carbon::createFromDate($year, $month, 1)->format('F Y');
        $startDate  = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate    = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        PayrollPeriod::firstOrCreate(
            ['period_name' => $periodName],
            [
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'status'     => 'draft',
            ]
        );

        return redirect()->back()->with('success', 'Pay period added successfully');
    }

    public function update(Request $request, string $id)
    {

        $request->validate([
            'pay_period' => 'required|string|max:255',
        ]);

        $payperiod = payperiod::findOrFail($id);


        $payperiod->pay_period = $request->input('pay_period');


        $payperiod->save();
        return redirect()->back()->with('success', 'payperiod updated successfully!');
    }

    public function destroy(string $id)
    {
        $payperiod = payperiod::find($id);

    if ($payperiod) {
        $payperiod->delete();
        return redirect()->back()->with('success', 'payperiod deleted successfully');
    } else {
        return redirect()->back()->with('error', 'payperiod not found');
    }
    }

}
