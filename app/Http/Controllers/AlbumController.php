<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use Spatie\QueryBuilder\QueryBuilder;

class AlbumController extends Controller
{
    protected function filterQuery()
    {
        return QueryBuilder::for(Album::class)
            ->allowedIncludes(['tracks', 'episodes']);
    }

    #[OA\Get(
        path: '/api/albums',
        description: 'List all albums.',
        summary: 'Get Albums'
    )]
    #[OA\Response(response: Response::HTTP_OK, description: 'OK')]
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
    )]
    #[OA\PathParameter(name: 'id', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\QueryParameter(name: 'include', schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'string', enum: ['albums', 'tracks'])), style: 'form', explode: false)]
    #[OA\Response(response: Response::HTTP_OK, description: 'OK', content: new OA\JsonContent(ref: AlbumResource::class))]
    #[OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Not Found')]
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
        summary: 'Get Random Album'
    )]
    #[OA\Response(response: Response::HTTP_OK, description: 'OK')]
    public function random()
    {
        return new AlbumResource(
            $this->filterQuery()
                ->inRandomOrder()->first()
        );
    }
}
