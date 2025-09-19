<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxTable extends Model
{
    protected $fillable = [
        'min',
        'max',
        'tax_percent',
        'add_amount'
    ];
}
