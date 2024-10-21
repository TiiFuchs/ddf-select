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
            'duration_in_millis' => $this->duration_in_millis,
            'duration_formatted' => $this->durationFormatted(),
            'release_date' => $this->release_date->format('Y-m-d'),

            'album' => new AlbumResource($this->whenLoaded('album')),

            'tracks' => $this->whenLoaded('tracks', fn () => $this->tracks->pluck('apple_music_id')),
        ];
    }
}
