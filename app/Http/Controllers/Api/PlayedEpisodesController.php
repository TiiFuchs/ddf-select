<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayedEpisodeResource;
use App\Models\PlayedEpisode;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\Paginator;

class PlayedEpisodesController extends Controller
{
    /**
     * List All Played Episodes
     *
     * @return AnonymousResourceCollection<Paginator<PlayedEpisodeResource>>
     */
    public function index(#[CurrentUser] User $user)
    {
        return PlayedEpisodeResource::collection(
            $user->playedEpisodes()
                ->simplePaginate(25)
        );
    }

    /**
     * Delete Played Episode
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function destroy(#[CurrentUser] User $user, PlayedEpisode $played)
    {
        abort_unless($played->user_id === $user->id, 403, 'Forbidden.');

        $played->delete();

        return new JsonResponse([
            'message' => 'OK',
        ]);
    }
}
