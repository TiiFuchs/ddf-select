<?php

namespace App\Models;

use App\Models\Scopes\OrderedTrackScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ScopedBy(OrderedTrackScope::class)]
class Track extends Model
{
    protected $guarded = [];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class);
    }

    public function episodes(): BelongsToMany
    {
        return $this->belongsToMany(Episode::class);
    }
}
