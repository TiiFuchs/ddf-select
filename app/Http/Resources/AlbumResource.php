<?php

namespace App\Http\Resources;

use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Album */
class AlbumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'apple_music_id' => $this->apple_music_id,
            'name' => $this->name,
            'track_count' => $this->track_count,
            'release_date' => $this->release_date->format('Y-m-d'),
            'url' => $this->url,
            'artwork' => $this->artwork,

            'episodes' => EpisodeResource::collection(
                $this->whenLoaded('episodes'),
            ),
        ];
    }
}
