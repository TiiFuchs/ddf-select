<?php

namespace App\Models;

use App\Data\AppleMusic\ArtworkData;
use App\Models\Scopes\NotIgnoredScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use RedExplosion\Sqids\Concerns\HasSqids;

#[ScopedBy(NotIgnoredScope::class)]
class Album extends Model
{
    use HasSqids;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'release_date' => 'date',
            'artwork' => ArtworkData::class,
        ];
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class);
    }

    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}
