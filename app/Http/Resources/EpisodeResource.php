<?php

namespace App\Http\Resources;

use App\Models\Episode;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/** @mixin Episode */
#[OA\Schema(
    properties: [
        new OA\Property('id', description: 'The identifier for the episode.', type: 'integer'),
        new OA\Property('number', description: 'Episode number if the episode has one', type: 'integer', nullable: true),
        new OA\Property('name', description: 'The name of the episode.', type: 'string'),
        new OA\Property('duration_in_millis', description: '', type: 'integer'),
        new OA\Property('duration_formatted', description: '', type: 'string'),
        new OA\Property('release_date', description: '', type: 'string', format: 'YYYY-mm-dd', nullable: true),
    ]
)]
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
