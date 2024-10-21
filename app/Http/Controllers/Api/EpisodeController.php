<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EpisodeResource;
use App\Models\Episode;
use Illuminate\Auth\RequestGuard;
use Illuminate\Container\Attributes\Auth as AuthGuard;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\Paginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EpisodeController extends Controller
{
    public const int RANDOM_IGNORE_DURATION_IN_WEEKS = 24;

    /**
     * List Episodes
     *
     * @unauthenticated
     *
     * @return AnonymousResourceCollection<Paginator<EpisodeResource>>
     */
    public function index()
    {
        return EpisodeResource::collection(
            QueryBuilder::for(Episode::class)
                ->allowedFilters([
                    /**
                     * Possible values are: `short` (<30 min), `normal` (30-90 min) or `long` (>=90 min)
                     *
                     * @var string
                     */
                    AllowedFilter::scope('duration'),
                ])
                ->allowedIncludes(['album', 'tracks'])
                ->simplePaginate(5)
                ->appends(request()->query())
        );
    }

    /**
     * Show Episode
     *
     * @unauthenticated
     */
    public function show($id)
    {
        $record = QueryBuilder::for(Episode::class)
            ->allowedIncludes(['album', 'tracks'])
            ->where('id', $id)
            ->first();

        abort_if($record === null, 404, 'Episode not found');

        return new EpisodeResource(
            $record
        );
    }

    /**
     * Get Random Episode
     *
     * Gets a random episode. <br><br> If authenticated, an additional meta property is added to specify how many
     * episodes were ignored, because they are were played recently.
     */
    public function random(#[AuthGuard('sanctum')] RequestGuard $auth): EpisodeResource
    {
        $query = QueryBuilder::for(Episode::class)
            ->allowedFilters([
                /**
                 * Possible values are: `short` (<30 min), `normal` (30-90 min) or `long` (>=90 min)
                 *
                 * @var string
                 */
                AllowedFilter::scope('duration'),
            ])
            ->allowedIncludes(['album', 'tracks'])
            ->inRandomOrder();

        if (! $auth->check()) {
            return new EpisodeResource(
                $query->firstOrFail()
            );
        }

        $shortenDuration = 0;

        do {
            $duration = self::RANDOM_IGNORE_DURATION_IN_WEEKS - $shortenDuration++;

            $ignoredEpisodes = $auth->user()->playedEpisodes()
                ->wherePivot('played_at', '>', now()->subWeeks($duration))
                ->select(['episodes.id', 'episodes.name'])
                ->get();

            $ignoreList = $ignoredEpisodes
                ->pluck('id')->unique();

            $episode = $query
                ->whereNotIn('id', $ignoreList)
                ->first();

        } while (! $episode && $duration > 0);

        abort_if($episode === null, 404);

        return (new EpisodeResource($episode))
            ->additional([
                'meta' => [
                    /**
                     * Only if request is authenticated
                     *
                     * @var int
                     * @example 2
                     */
                    'ignored_episodes_count' => $ignoreList->count(),
                ],
            ]);
    }
}
