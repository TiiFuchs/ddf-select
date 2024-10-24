<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use RedExplosion\Sqids\Concerns\HasSqids;

class Episode extends Model
{
    use HasSqids;

    public const int DURATION_THRESHOLD_SHORT = 1800000; // 30 minutes

    public const int DURATION_THRESHOLD_LONG = 5400000; // 90 minutes

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

    public function plays(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'played_episodes')
            ->withPivot(['id', 'played_at'])
            ->orderByPivot('played_at', 'desc')
            ->using(PlayedEpisode::class);
    }

    public function scopeDuration(Builder $query, EpisodeDuration|string $episodeLength): void
    {
        if (is_string($episodeLength)) {
            $episodeLength = EpisodeDuration::from($episodeLength);
        }

        match ($episodeLength) {
            EpisodeDuration::Short => $query->where('duration_in_millis', '<', self::DURATION_THRESHOLD_SHORT),

            EpisodeDuration::Normal => $query->where('duration_in_millis', '>=', self::DURATION_THRESHOLD_SHORT)
                ->where('duration_in_millis', '<', self::DURATION_THRESHOLD_LONG),

            EpisodeDuration::Long => $query->where('duration_in_millis', '>=', self::DURATION_THRESHOLD_LONG),
        };
    }

    public function durationFormatted(): string
    {
        return CarbonInterval::milliseconds($this->duration_in_millis)->cascade()
            ->forHumans(short: true, parts: 2);
    }
}
