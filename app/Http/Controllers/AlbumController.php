<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlbumResource;
use App\Models\Album;
use Spatie\QueryBuilder\QueryBuilder;

class AlbumController extends Controller
{
    protected function filterQuery()
    {
        return QueryBuilder::for(Album::class)
            ->allowedIncludes(['tracks', 'episodes']);
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
                ->first()
        );
    }

    public function random()
    {
        return new AlbumResource(
            $this->filterQuery()
                ->inRandomOrder()->first()
        );
    }
}
