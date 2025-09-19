<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Department;
use App\Models\GeneralFactor;
use App\Models\RatingScale;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::with(['department', 'generalFactor', 'ratingScale'])->get();
        return view('performance.evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $departments = Department::all();
        $generalFactors = GeneralFactor::where('status', 'Active')->get();
        $ratingScales = RatingScale::where('status', 'Active')->get();

        return view('performance.evaluations.create', compact('departments', 'generalFactors', 'ratingScales'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'evaluation_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'general_factor_id' => 'required|exists:general_factors,id',
            'rating_scale_id' => 'required|exists:rating_scales,id',
            'evaluation_period_start' => 'required|date',
            'evaluation_period_end' => 'required|date|after_or_equal:evaluation_period_start',
            'description' => 'nullable|string',
            'status' => 'required|in:Draft,Active,Completed,Inactive'
        ]);

        Evaluation::create($request->all());

        return redirect()->route('evaluations.index')
                        ->with('success', 'Evaluation created successfully!');
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['department', 'generalFactor', 'ratingScale', 'employeeEvaluations']);
        return view('performance.evaluations.show', compact('evaluation'));
    }

    public function edit(Evaluation $evaluation)
    {
        $departments = Department::where('status', 'Active')->get();
        $generalFactors = GeneralFactor::where('status', 'Active')->get();
        $ratingScales = RatingScale::where('status', 'Active')->get();

        return view('performance.evaluations.edit', compact('evaluation', 'departments', 'generalFactors', 'ratingScales'));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'evaluation_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'general_factor_id' => 'required|exists:general_factors,id',
            'rating_scale_id' => 'required|exists:rating_scales,id',
            'evaluation_period_start' => 'required|date',
            'evaluation_period_end' => 'required|date|after_or_equal:evaluation_period_start',
            'description' => 'nullable|string',
            'status' => 'required|in:Draft,Active,Completed,Inactive'
        ]);

        $evaluation->update($request->all());

        return redirect()->route('evaluations.index')
                        ->with('success', 'Evaluation updated successfully!');
    }

    public function destroy(Evaluation $evaluation)
    {
        if ($evaluation->employeeEvaluations()->count() > 0) {
            return redirect()->route('evaluations.index')
                            ->with('error', 'Cannot delete evaluation that has employee evaluations!');
        }

        $evaluation->delete();

        return redirect()->route('evaluations.index')
                        ->with('success', 'Evaluation deleted successfully!');
    }
}
