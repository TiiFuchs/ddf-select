<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Spatie\QueryBuilder\QueryBuilder;

class AlbumController extends Controller
{
    protected function filterQuery()
    {
        return QueryBuilder::for(Album::class)
            ->allowedIncludes(['episodes.tracks']);
    }

    public function index()
    {
        return AlbumResource::collection(
            $this->filterQuery()
                ->simplePaginate(5)
        );
    }

    public function show($id)
    {
        return new AlbumResource(
            $this->filterQuery()
                ->whereId($id)
                ->firstOrFail()
        );
    }

    public function random()
    {
        return new AlbumResource(
            $this->filterQuery()
                ->inRandomOrder()->firstOrFail()
        );
    }
}
