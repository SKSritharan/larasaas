<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    protected $fillable = ['name', 'description'];

    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class)->withPivot('value');
    }
}
