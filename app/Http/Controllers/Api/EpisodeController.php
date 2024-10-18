<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EpisodeResource;
use App\Models\Episode;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

#[OA\Tag('Episodes', 'Endpoints related to episodes')]
class EpisodeController extends Controller
{
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
    public function random()
    {
        return new EpisodeResource(
            $this->applyFilter()
                ->inRandomOrder()->firstOrFail()
        );
    }
}
