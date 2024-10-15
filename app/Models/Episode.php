<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Episode extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
        ];
    }

    public function tracks(): BelongsToMany
    {
        return $this->belongsToMany(Track::class);
    }

    public function durationInMinutes(): Attribute
    {
        return Attribute::get(function ($value, array $attributes) {
            return round($attributes['duration_in_millis'] / 1000 / 60, 2);
        });
    }
}
