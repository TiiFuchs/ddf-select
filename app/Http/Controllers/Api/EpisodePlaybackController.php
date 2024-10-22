<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Episode;
use App\Models\PlayedEpisode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

class EpisodePlaybackController extends Controller
{
    /**
     * List Episode Playbacks
     *
     * @param User $user
     * @return JsonResponse
     */
    public function index(Episode $episode, #[CurrentUser] User $user)
    {
        $played = $user->playedEpisodes()->where('episodes.id', $episode->id)->get();

        return new JsonResponse([
            /**
             * @var string[]
             * @example [
             * "2024-10-21T09:46:04.000000Z",
             * "2024-10-20T09:36:51.000000Z"
             * ]
             */
            'data' => $played->pluck('pivot.played_at'),
        ]);
    }

    /**
     * Mark Episode as Played
     *
     * Marks the episode as played at the current datetime. <br><br>
     * If an episode was already marked as played in the last 5 minutes, the older play-state gets discarded.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function store(Episode $episode, #[CurrentUser] User $user)
    {
        $lastPlayedEpisode = $user->playedEpisodes()->first();
        if ($lastPlayedEpisode) {
            /** @var Carbon $lastPlayedAt */
            $lastPlayedAt = $lastPlayedEpisode->pivot->played_at;

            if ($lastPlayedAt->diff()->totalMinutes < 5) {
                PlayedEpisode::where('id', $lastPlayedEpisode->pivot->id)->delete();
            }
        }

        $user->playedEpisodes()->attach($episode->id);

        return new JsonResponse([
            'message' => 'Created.',
        ], 201);
    }
}
