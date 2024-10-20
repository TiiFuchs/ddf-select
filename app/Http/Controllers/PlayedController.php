<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\PlayedEpisode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

class PlayedController extends Controller
{
    public function index(Episode $episode, #[CurrentUser] User $user)
    {
        $played = $user->playedEpisodes()->where('episodes.id', $episode->id)->get();

        return new JsonResponse([
            'data' => $played->pluck('pivot.played_at'),
        ]);
    }

    public function store($episode, #[CurrentUser] User $user)
    {
        $lastPlayedEpisode = $user->playedEpisodes()->first();
        if ($lastPlayedEpisode) {
            /** @var Carbon $lastPlayedAt */
            $lastPlayedAt = $lastPlayedEpisode->pivot->played_at;

            if ($lastPlayedAt->diff()->totalMinutes < 5) {
                PlayedEpisode::where('id', $lastPlayedEpisode->pivot->id)->delete();
            }
        }

        $user->playedEpisodes()->attach($episode);

        return new JsonResponse(['message' => 'Created'], 201);
    }
}
