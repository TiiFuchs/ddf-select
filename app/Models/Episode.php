<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
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

    public function durationFormatted(): string
    {
        return CarbonInterval::milliseconds($this->duration_in_millis)->cascade()
            ->forHumans(short: true);
    }
}
