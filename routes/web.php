<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EarngroupController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobtitleController;
use App\Http\Controllers\holidayController;
use App\Http\Controllers\companyController;
use App\Http\Controllers\advanceController;
use App\Http\Controllers\allowanceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\loanController;
use App\Http\Controllers\pensionController;
use App\Http\Controllers\terminationController;
use App\Http\Controllers\religionController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\leavetypeController;
use App\Http\Controllers\loantypeController;
use App\Http\Controllers\taxrateController;
use App\Http\Controllers\taxtableController;
use App\Http\Controllers\stafflevelController;
use App\Http\Controllers\nationalityController;
use App\Http\Controllers\MainstationController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\PaygradeController;
use App\Http\Controllers\PayPeriodController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SubstationController;
use App\Http\Controllers\SupervisorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralFactorController;
use App\Http\Controllers\FactorController;
use App\Http\Controllers\SubFactorController;
use App\Http\Controllers\RatingScaleController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/dashboard', [DashboardController::class, 'home'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route::get('/users/list', [DashboardController::class, 'user'])->name('users.index');
    // Route::post('/register', [DashboardController::class, 'register']);
    // Route::delete('/user/{id}', [DashboardController::class, 'destroy'])->name('user.destroy');

    Route::resource('skill', SkillController::class);
    Route::resource('nationality', nationalityController::class);
    Route::resource('loantype', loantypeController::class);
    Route::resource('leavetype', leavetypeController::class);
    Route::resource('termination', terminationController::class);
    Route::resource('loan', loanController::class);
    Route::post('loan/{loan}/installments', [LoanController::class, 'storeInstallments'])->name('loan.installments.store');
    Route::resource('advance', advanceController::class);

    Route::resource('language', LanguageController::class);
    Route::resource('company', companyController::class);
    Route::resource('pension', pensionController::class);
    Route::resource('holiday', holidayController::class);
    Route::resource('religion', religionController::class);
    Route::resource('payment', paymentController::class);
    Route::resource('stafflevel', stafflevelController::class);
    Route::resource('taxtable', taxtableController::class);
    Route::resource('taxrate', taxrateController::class);
    Route::resource('education', EducationController::class);
    Route::resource('bank', BankController::class);
    Route::resource('relation', RelationController::class);
    Route::resource('payperiod', PayPeriodController::class);
    Route::resource('department', DepartmentController::class);
    Route::resource('occupation', OccupationController::class);
    Route::resource('supervisor', SupervisorController::class);
    Route::resource('reporting', ReportingController::class);
    Route::resource('earngroup', EarngroupController::class);
    Route::resource('job_title', JobtitleController::class);
    Route::resource('pay_grade', PaygradeController::class);
    Route::resource('mainstation',  MainstationController::class);
    Route::resource('substation',  SubstationController::class);
    // Route::resource('employee',  EmployeeController::class);

    Route::resource('roles', RolesController::class);
    Route::get('/users', [UsersController::class, 'user'])->name('users.index');
    Route::post('/users/register', [UsersController::class, 'register'])->name('user.register');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::post('/user/toggle-status/{id}', [UsersController::class, 'toggleStatus'])->name('user.toggleStatus');
    Route::delete('/user/{id}', [UsersController::class, 'destroy'])->name('user.destroy');

    Route::resource('allowance', allowanceController::class);

    Route::prefix('allowance_details')->name('direct.')->group(function () {
        Route::get('/', [AllowanceController::class, 'details'])->name('index');
        Route::post('/', [AllowanceController::class, 'detailsStore'])->name('store');
        Route::put('/{id}', [AllowanceController::class, 'detailsUpdate'])->name('update');
        Route::delete('/{id}', [AllowanceController::class, 'detailsDestroy'])->name('destroy');
    });

    Route::prefix('leaves')->name('leaves.')->group(function () {
        Route::get('/', [LeaveController::class, 'index'])->name('index');
        Route::get('/create', [LeaveController::class, 'create'])->name('create');
        Route::post('/store', [LeaveController::class, 'store'])->name('store');
        Route::get('/{leave}', [LeaveController::class, 'show'])->name('show');
        Route::get('/{leave}/edit', [LeaveController::class, 'edit'])->name('edit');
        Route::put('/{leave}', [LeaveController::class, 'update'])->name('update');
        Route::delete('/{leave}', [LeaveController::class, 'destroy'])->name('destroy');
    });

    // API route for getting leave type details
    Route::get('/api/leave-types/{id}', [LeaveController::class, 'getLeaveTypeDetails'])->name('api.leave-types.details');




// Performance Management Routes
Route::prefix('performance')->name('performance.')->group(function () {

    // General Factors Management
    Route::resource('general-factors', GeneralFactorController::class);

    // Factors Management
    Route::resource('factors', FactorController::class);

    // Sub Factors Management
    Route::resource('sub-factors', SubFactorController::class);

    // Rating Scales Management
    Route::resource('rating-scales', RatingScaleController::class);

    // Evaluations Management
    Route::resource('evaluations', EvaluationController::class);
});

// Alternative route structure (if you prefer without prefix)
Route::resource('general-factors', GeneralFactorController::class);
Route::resource('factors', FactorController::class);
Route::resource('sub-factors', SubFactorController::class);
Route::resource('rating-scales', RatingScaleController::class);
Route::resource('evaluations', EvaluationController::class);

// API routes for dynamic data loading
Route::prefix('api')->name('api.')->group(function () {
    // Get factors by general factor
    Route::get('general-factors/{id}/factors', [GeneralFactorController::class, 'getFactors'])
         ->name('general-factors.factors');

    // Get sub-factors by factor
    Route::get('factors/{id}/sub-factors', [FactorController::class, 'getSubFactors'])
         ->name('factors.sub-factors');

    // Get rating scale items by rating scale
    Route::get('rating-scales/{id}/items', [RatingScaleController::class, 'getRatingScaleItems'])
         ->name('rating-scales.items');

    // Get general factor details
    Route::get('general-factors/{id}', [GeneralFactorController::class, 'show'])
         ->name('general-factors.show');
});


    Route::get('/employees', [EmployeeController::class, 'index'])->name('employee.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employee.create');
    Route::post('/employees/store', [EmployeeController::class, 'store'])->name('employee.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employee.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy'])->name('employee.destroy');

    // Additional routes for AJAX requests
    Route::get('/api/banks', [EmployeeController::class, 'getBanks'])->name('api.banks');
    Route::get('/api/substations', [EmployeeController::class, 'getSubstations'])->name('api.substations');
    Route::get('/api/departments', [EmployeeController::class, 'getDepartments'])->name('api.departments');


    Route::get('payroll/process',[PayrollController::class,'index'])->name('payroll.index');
    Route::post('payroll/process-selected', [PayrollController::class, 'processSelected'])->name('payroll.processSelected');
    Route::post('payroll/process-all', [PayrollController::class, 'processAll'])->name('payroll.processAll');
    Route::get('payroll/{id}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::delete('payroll/cancel', [PayrollController::class, 'destroy'])->name('payroll.cancel');
    Route::get('api/payroll/periods', [PayrollController::class, 'getPeriods'])->name('payroll.periods');

    //enable and disable user
    Route::post('/user/toggle-status/{id}', [ProfileController::class, 'toggleStatus'])->name('user.toggleStatus');
});

require __DIR__ . '/auth.php';
