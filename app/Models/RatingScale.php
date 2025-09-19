<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RatingScale extends Model
{
    protected $fillable = [
        'scale_name',
        'description',
        'status'
    ];

    public function ratingScaleItems(): HasMany
    {
        return $this->hasMany(RatingScaleItem::class)->orderBy('sort_order');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}
