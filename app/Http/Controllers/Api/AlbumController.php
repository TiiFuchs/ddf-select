<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\QueryBuilder;

#[OA\Tag('Albums', 'Endpoints related to albums')]
class AlbumController extends Controller
{
    #[OA\QueryParameter('albumInclude', name: 'include', description: 'Include relationships', schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['episodes', 'episodes.tracks'])), style: 'form', explode: false)]
    protected function filterQuery()
    {
        return QueryBuilder::for(Album::class)
            ->allowedIncludes(['episodes.tracks']);
    }

    #[OA\Get(
        path: '/api/albums',
        description: 'List all albums.',
        summary: 'Get Albums',
        tags: ['Albums'],
        parameters: [
            new OA\QueryParameter(ref: '#/components/parameters/albumInclude'),
        ], responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'OK'),
        ]
    )]
    public function index()
    {
        return AlbumResource::collection(
            $this->filterQuery()
                ->simplePaginate(5)
        );
    }

    #[OA\Get(
        path: '/api/albums/{id}',
        description: 'Shows a specific album.',
        summary: 'Show Album',
        tags: ['Albums'],
        parameters: [
            new OA\PathParameter(name: 'id', description: 'The unique identifier of the album', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\QueryParameter(ref: '#/components/parameters/albumInclude'),
        ], responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'OK', content: new OA\JsonContent(ref: AlbumResource::class)),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Not Found'),
        ]
    )]
    public function show($id)
    {
        return new AlbumResource(
            $this->filterQuery()
                ->whereId($id)
                ->firstOrFail()
        );
    }

    #[OA\Get(
        path: '/api/albums/random',
        description: 'Shows a random album.',
        summary: 'Get Random Album',
        tags: ['Albums'],
        parameters: [
            new OA\QueryParameter(ref: '#/components/parameters/albumInclude'),
        ], responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'OK', content: new OA\JsonContent(ref: AlbumResource::class)),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Not Found'),
        ]
    )]
    public function random()
    {
        return new AlbumResource(
            $this->filterQuery()
                ->inRandomOrder()->firstOrFail()
        );
    }
}
