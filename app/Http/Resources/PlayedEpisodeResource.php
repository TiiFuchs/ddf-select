<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayedEpisodeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /** @example 30 */
            'id' => $this->pivot->id,
            /**
             * @var string
             * @format date-time
             * @example 2024-10-22T06:32:43Z
             */
            'played_at' => $this->pivot->played_at->toIso8601String(),
            'episode' => new EpisodeResource(
                $this
            ),
        ];
    }
}
