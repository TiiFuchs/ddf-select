<?php

namespace App\Http\Resources;

use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Episode */
class EpisodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'name' => $this->name,
            'duration_in_minutes' => $this->duration_in_minutes,
            'duration_formatted' => $this->durationFormatted(),
            'release_date' => $this->release_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'tracks_count' => $this->tracks_count,
            'tracks' => $this->tracks->pluck('am_id'),
        ];
    }
}
