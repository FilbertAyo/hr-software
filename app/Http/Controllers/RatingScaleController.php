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
        return redirect()->route('rating-scales.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'scale_name' => 'required|string|max:255|unique:rating_scales',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.score' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request) {
            $ratingScale = RatingScale::create([
                'scale_name' => $request->scale_name,
            ]);

            foreach ($request->items as $index => $item) {
                RatingScaleItem::create([
                    'rating_scale_id' => $ratingScale->id,
                    'item_name' => $item['item_name'],
                    'score' => $item['score'],
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
        return redirect()->route('rating-scales.index');
    }

    public function update(Request $request, RatingScale $ratingScale)
    {
        $request->validate([
            'scale_name' => 'required|string|max:255|unique:rating_scales,scale_name,' . $ratingScale->id,
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.score' => 'required|numeric|min:0'
        ]);

        DB::transaction(function () use ($request, $ratingScale) {
            $ratingScale->update($request->only(['scale_name']));

            // Delete existing items and create new ones
            $ratingScale->ratingScaleItems()->delete();

            foreach ($request->items as $index => $item) {
                RatingScaleItem::create([
                    'rating_scale_id' => $ratingScale->id,
                    'item_name' => $item['item_name'],
                    'score' => $item['score'],
                ]);
            }
        });

        return redirect()->route('rating-scales.index')
                        ->with('success', 'Rating Scale updated successfully!');
    }

    public function destroy(RatingScale $ratingScale)
    {
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

