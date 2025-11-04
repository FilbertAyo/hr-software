<?php

namespace App\Http\Controllers;

use App\Models\Mainstation;
use App\Models\Substation;
use App\Models\User;
use App\Models\Employee;
use App\Models\PayrollPeriod;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function home()
    {
        // Current date
        $currentDate = now();
        
        // Get current payroll period
        $currentPayrollPeriod = PayrollPeriod::where('start_date', '<=', $currentDate)
            ->where('end_date', '>=', $currentDate)
            ->first();
            
        // Get previous payroll period
        $previousPayrollPeriod = PayrollPeriod::where('end_date', '<', $currentDate)
            ->orderBy('end_date', 'desc')
            ->first();
            
        // Get employee counts
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('employee_status', 'active')->count();
        $onLeave = Employee::where('employee_status', 'onhold')->count();
        
        // Get payroll summary for the current period if it exists
        $payrollSummary = [];
        if ($currentPayrollPeriod) {
            $payrollSummary = Payroll::where('payroll_period_id', $currentPayrollPeriod->id)
                ->select(
                    DB::raw('SUM(gross_salary) as total_gross'),
                    DB::raw('SUM(total_deductions) as total_deductions'),
                    DB::raw('SUM(net_salary) as total_net'),
                    DB::raw('COUNT(*) as employee_count')
                )->first();
        }
        
        // Get station counts
        $substation = Substation::count();
        $mainstation = Mainstation::count();
        
        // User counts
        $user = User::count();
        
        return view('dashboard', compact(
            'substation',
            'mainstation',
            'user',
            'currentPayrollPeriod',
            'previousPayrollPeriod',
            'totalEmployees',
            'activeEmployees',
            'onLeave',
            'payrollSummary',
            'currentDate'
        ));
    }

    public function user()
    {

        $user= User::all();
        return view('users.index',compact('user'));
    }

    public function register(Request $request){
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed',Rules\Password::defaults()->min(6),],
        ]);

        $user = User::create([
            'name' => $request->name,
            'status' => $request->status,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return redirect()->back()->with('success','New user added successfully');
    }

public function destroy($id)
{
    $user = User::find($id);

    if ($user) {
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    } else {
        return redirect()->back()->with('error', 'User not found');
    }
}

}
