<?php

namespace App\Http\Resources;

use App\Data\AppleMusic\ArtworkData;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

/** @mixin Album */
#[OA\Schema(properties: [
    new OA\Property('id', description: 'The identifier for the album.', type: 'integer'),
    new OA\Property('apple_music_id', description: 'The Apple Music identifier for the album.', type: 'string'),
    new OA\Property('name', description: 'The localized name of the album.', type: 'string'),
    new OA\Property('track_count', description: 'The number of tracks for the album.', type: 'integer'),
    new OA\Property('release_date', description: 'The release date of the album, when known, in YYYY-MM-DD or YYYY format. Prerelease content may have an expected release date in the future.', type: 'string'),
    new OA\Property('url', description: 'The URL for sharing the album in Apple Music.', type: 'string'),
    new OA\Property('artwork', ref: ArtworkData::class, description: 'The artwork for the album.', type: 'object'),
])]
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

            'tracks' => $this->whenLoaded('tracks', fn () => $this->tracks->pluck('apple_music_id')),

            'episodes' => EpisodeResource::collection(
                $this->whenLoaded('episodes'),
            ),
        ];
    }
}
