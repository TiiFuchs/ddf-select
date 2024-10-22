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
            /** @example 188 */
            'id' => $this->id,
            /**
             * @var int|null
             * @example 141
             */
            'number' => $this->number,
            /** @example und die Fußball-Falle */
            'name' => $this->name,
            /** @example 3596932 */
            'duration_in_millis' => $this->duration_in_millis,
            /** @example 59 Min. 56 Sek. */
            'duration_formatted' => $this->durationFormatted(),
            /**
             * @var string|null
             * @format date
             * @example 2010-10-01
             */
            'release_date' => $this->release_date?->format('Y-m-d') ?? null,

            /** @var bool */
            'album_exists' => $this->when(isset($this->album_exists), $this->album_exists),
            /** @example 1 */
            'album_count' => $this->whenCounted('album'),
            'album' => new AlbumResource($this->whenLoaded('album')),

            /** @var bool */
            'tracks_exists' => $this->when(isset($this->tracks_exists), $this->tracks_exists),
            /** @example 38 */
            'tracks_count' => $this->whenCounted('tracks'),
            /**
             * List of Apple Music IDs for the tracks featured in this episode.
             *
             * @var string[]
             * @example [1436693013, 1436693014, 1436693015, 1436693016, 1436693017]
             */
            'tracks' => $this->whenLoaded('tracks', fn () => $this->tracks->pluck('apple_music_id')),
        ];
    }
}
