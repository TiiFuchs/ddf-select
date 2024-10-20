<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PlayedEpisode extends Pivot
{
    public $incrementing = true;

    public $timestamps = false;

    protected $table = 'played_episodes';

    protected static function booted()
    {
        static::creating(function (PlayedEpisode $playedEpisode) {
            $playedEpisode->played_at ??= now();
        });
    }

    protected function casts(): array
    {
        return [
            'played_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function episode(): BelongsTo
    {
        return $this->belongsTo(Episode::class);
    }
}
