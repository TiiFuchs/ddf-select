<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EpisodeResource;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Auth\RequestGuard;
use Illuminate\Container\Attributes\Auth as AuthGuard;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[OA\Tag('Episodes', 'Endpoints related to episodes')]
class EpisodeController extends Controller
{
    public const int RANDOM_IGNORE_DURATION_IN_WEEKS = 24;

    #[OA\QueryParameter('episodeInclude', name: 'include', schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['album', 'tracks'])), style: 'form', explode: false)]
    #[OA\QueryParameter('episodeDuration', name: 'filter[duration]', schema: new OA\Schema(type: 'string', enum: ['short', 'normal', 'long']))]
    protected function applyFilter()
    {
        return QueryBuilder::for(Episode::class)
            ->allowedFilters([
                AllowedFilter::scope('duration'),
            ])
            ->allowedIncludes(['album', 'tracks']);
    }

    #[OA\Get(
        path: '/api/episodes',
        description: 'List all episodes',
        summary: 'List All Episodes',
        tags: ['Episodes'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/episodeInclude'),
            new OA\Parameter(ref: '#/components/parameters/episodeDuration'),
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'OK'),
            new OA\Response(ref: '#/components/responses/401', response: Response::HTTP_UNAUTHORIZED),
        ]
    )]
    public function index()
    {
        return EpisodeResource::collection(
            $this->applyFilter()
                ->simplePaginate(5)
        );
    }

    #[OA\Get(
        path: '/api/episodes/{id}',
        description: 'Get episode',
        summary: 'Get Episode',
        tags: ['Episodes'],
        parameters: [
            new OA\PathParameter(name: 'id', description: 'The unique identifier of the episode', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(ref: '#/components/parameters/episodeInclude'),
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'OK', content: new OA\JsonContent(ref: EpisodeResource::class)),
            new OA\Response(ref: '#/components/responses/401', response: Response::HTTP_UNAUTHORIZED),
            new OA\Response(ref: '#/components/responses/404', response: Response::HTTP_NOT_FOUND),
        ]
    )]
    public function show($id)
    {
        return new EpisodeResource(
            $this->applyFilter()
                ->whereId($id)
                ->firstOrFail(),
        );
    }

    #[OA\Get(
        path: '/api/episodes/random',
        description: 'Get a random episode,',
        summary: 'Get Random Episode,',
        tags: ['Episodes'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/episodeInclude'),
            new OA\Parameter(ref: '#/components/parameters/episodeDuration'),
        ], responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'OK', content: new OA\JsonContent(ref: EpisodeResource::class)),
            new OA\Response(ref: '#/components/responses/401', response: Response::HTTP_UNAUTHORIZED),
        ]
    )]
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
