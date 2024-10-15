<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function tracks(): BelongsToMany
    {
        return $this->belongsToMany(Track::class);
    }

    public function scopeRandom(Builder $query): void
    {
        $query->orderByRaw('RANDOM()');
    }

    public function durationInMinutes(): Attribute
    {
        return Attribute::get(function ($value, array $attributes) {
            return round($attributes['duration_in_millis'] / 1000 / 60, 3);
        });
    }

    public function durationFormatted(): string
    {
        $minutes = floor($this->duration_in_millis / 1000 / 60);
        $seconds = floor($this->duration_in_millis / 1000 % 60);

        return "{$minutes}m{$seconds}s";
    }
}
