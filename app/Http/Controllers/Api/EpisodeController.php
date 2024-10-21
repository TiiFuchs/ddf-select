<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EpisodeResource;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Auth\RequestGuard;
use Illuminate\Container\Attributes\Auth as AuthGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EpisodeController extends Controller
{
    public const int RANDOM_IGNORE_DURATION_IN_WEEKS = 24;

    protected function applyFilter()
    {
        return QueryBuilder::for(Episode::class)
            ->allowedFilters([
                AllowedFilter::scope('duration'),
            ])
            ->allowedIncludes(['album', 'tracks']);
    }

    public function index()
    {
        return EpisodeResource::collection(
            $this->applyFilter()
                ->simplePaginate(5)
        );
    }

    public function show($id)
    {
        return new EpisodeResource(
            $this->applyFilter()
                ->whereId($id)
                ->firstOrFail(),
        );
    }

    public function random(#[AuthGuard('sanctum')] RequestGuard $auth)
    {
        return $auth->check()
            ? $this->randomEpisodeUserFiltered($auth->user())
            : $this->randomEpisode();
    }

    protected function randomEpisode(): EpisodeResource
    {
        return new EpisodeResource(
            $this->applyFilter()
                ->inRandomOrder()
                ->firstOrFail()
        );
    }

    protected function randomEpisodeUserFiltered(Authenticatable|User $user): EpisodeResource
    {
        $shortenDuration = 0;

        do {
            $duration = self::RANDOM_IGNORE_DURATION_IN_WEEKS - $shortenDuration++;

            $ignoredEpisodes = $user->playedEpisodes()
                ->wherePivot('played_at', '>', now()->subWeeks($duration))
                ->select(['episodes.id', 'episodes.name'])
                ->get();

            $ignoreList = $ignoredEpisodes
                ->pluck('id')->unique();

            $episode = $this->applyFilter()
                ->whereNotIn('id', $ignoreList)
                ->inRandomOrder()
                ->first();

        } while (! $episode && $duration > 0);

        if (! $episode) {
            throw new NotFoundHttpException('Record not found.');
        }

        return (new EpisodeResource($episode))
            ->additional([
                'meta' => [
                    'ignored_episodes_count' => $ignoreList->count(),
                ],
            ]);
    }
}
