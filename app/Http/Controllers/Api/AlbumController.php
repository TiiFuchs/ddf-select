<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\Paginator;
use Spatie\QueryBuilder\QueryBuilder;

class AlbumController extends Controller
{
    /**
     * List Albums
     *
     * @unauthenticated
     *
     * @return AnonymousResourceCollection<Paginator<AlbumResource>>
     */
    public function index()
    {
        return AlbumResource::collection(
            QueryBuilder::for(Album::class)
                ->allowedIncludes(['episodes', 'episodes.tracks'])
                ->simplePaginate(5)
        );
    }

    /**
     * Show Album
     *
     * @unauthenticated
     */
    public function show($id): AlbumResource
    {
        $resource = QueryBuilder::for(Album::class)
            ->allowedIncludes(['episodes', 'episodes.tracks'])
            ->where('id', $id)
            ->first();

        abort_if($resource === null, 404);

        return new AlbumResource(
            $resource
        );
    }

    /**
     * Get Random Album
     *
     * Gets a random album.
     *
     * @unauthenticated
     */
    public function random(): AlbumResource
    {
        $resource = QueryBuilder::for(Album::class)
            ->allowedIncludes(['episodes', 'episodes.tracks'])
            ->inRandomOrder()
            ->first();

        abort_if($resource === null, 404);

        return new AlbumResource(
            $resource
        );
    }
}
