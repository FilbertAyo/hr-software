<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::query()->get();
        return view('performance.evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        return redirect()->route('evaluations.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'evaluation_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,completed,cancelled'
        ]);

        Evaluation::create($request->only(['evaluation_name','start_date','end_date','status']));

        return redirect()->route('evaluations.index')
                        ->with('success', 'Evaluation created successfully!');
    }

    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['employeeEvaluations']);
        return view('performance.evaluations.show', compact('evaluation'));
    }

    public function edit(Evaluation $evaluation)
    {
        return redirect()->route('evaluations.index');
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $request->validate([
            'evaluation_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:draft,active,completed,cancelled'
        ]);

        $evaluation->update($request->only(['evaluation_name','start_date','end_date','status']));

        return redirect()->route('evaluations.index')
                        ->with('success', 'Evaluation updated successfully!');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return redirect()->route('evaluations.index')
                        ->with('success', 'Evaluation deleted successfully!');
    }
}

