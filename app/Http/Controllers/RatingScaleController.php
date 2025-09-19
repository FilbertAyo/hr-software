<?php

namespace App\Http\Controllers;

use App\Models\RatingScale;
use App\Models\RatingScaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RatingScaleController extends Controller
{
    public function index()
    {
        $ratingScales = RatingScale::withCount('ratingScaleItems')
                                  ->with('ratingScaleItems')
                                  ->get();
        return view('performance.rating-scales.index', compact('ratingScales'));
    }

    public function create()
    {
        return view('performance.rating-scales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'scale_name' => 'required|string|max:255|unique:rating_scales',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.score' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            $ratingScale = RatingScale::create([
                'scale_name' => $request->scale_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            foreach ($request->items as $index => $item) {
                RatingScaleItem::create([
                    'rating_scale_id' => $ratingScale->id,
                    'name' => $item['name'],
                    'score' => $item['score'],
                    'description' => $item['description'] ?? null,
                    'sort_order' => $index + 1
                ]);
            }
        });

        return redirect()->route('rating-scales.index')
                        ->with('success', 'Rating Scale created successfully!');
    }

    public function show(RatingScale $ratingScale)
    {
        $ratingScale->load('ratingScaleItems');
        return view('performance.rating-scales.show', compact('ratingScale'));
    }

    public function edit(RatingScale $ratingScale)
    {
        $ratingScale->load('ratingScaleItems');
        return view('performance.rating-scales.edit', compact('ratingScale'));
    }

    public function update(Request $request, RatingScale $ratingScale)
    {
        $request->validate([
            'scale_name' => 'required|string|max:255|unique:rating_scales,scale_name,' . $ratingScale->id,
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.score' => 'required|numeric|min:0',
            'items.*.description' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request, $ratingScale) {
            $ratingScale->update([
                'scale_name' => $request->scale_name,
                'description' => $request->description,
                'status' => $request->status
            ]);

            // Delete existing items and create new ones
            $ratingScale->ratingScaleItems()->delete();

            foreach ($request->items as $index => $item) {
                RatingScaleItem::create([
                    'rating_scale_id' => $ratingScale->id,
                    'name' => $item['name'],
                    'score' => $item['score'],
                    'description' => $item['description'] ?? null,
                    'sort_order' => $index + 1
                ]);
            }
        });

        return redirect()->route('rating-scales.index')
                        ->with('success', 'Rating Scale updated successfully!');
    }

    public function destroy(RatingScale $ratingScale)
    {
        if ($ratingScale->evaluations()->count() > 0) {
            return redirect()->route('rating-scales.index')
                            ->with('error', 'Cannot delete Rating Scale that is being used in evaluations!');
        }

        DB::transaction(function () use ($ratingScale) {
            $ratingScale->ratingScaleItems()->delete();
            $ratingScale->delete();
        });

        return redirect()->route('rating-scales.index')
                        ->with('success', 'Rating Scale deleted successfully!');
    }

    // API method for getting rating scale items
    public function getRatingScaleItems($id)
    {
        $items = RatingScale::find($id)?->ratingScaleItems;

        return response()->json($items ?? []);
    }
}
