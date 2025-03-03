<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'amount',
        'interval',
        'product_id',
        'price_id',
        'status',
    ];

    protected $casts= [
        'status' => 'boolean',
    ];
}
