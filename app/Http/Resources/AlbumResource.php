<?php

namespace App\Http\Resources;

use App\Data\AppleMusic\ArtworkData;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Album */
class AlbumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /** @example 195 */
            'id' => $this->id,
            /** @example 1148996010 */
            'apple_music_id' => $this->apple_music_id,
            /** @example Folge 141: und die Fußball-Falle */
            'name' => $this->name,
            /** @example 40 */
            'track_count' => $this->track_count,
            /**
             * @var string|null
             * @format date
             * @example 2010-10-01
             */
            'release_date' => $this->release_date?->format('Y-m-d'),
            /** @example https://music.apple.com/de/album/folge-141-und-die-fu%C3%9Fball-falle/1148996010 */
            'url' => $this->url,
            /** @var ArtworkData */
            'artwork' => $this->artwork,

            'episodes_count' => $this->whenCounted('episodes'),
            /** @var bool */
            'episodes_exists' => $this->when(isset($this->episodes_exists), $this->episodes_exists),

            'episodes' => EpisodeResource::collection(
                $this->whenLoaded('episodes'),
            ),
        ];
    }
}
