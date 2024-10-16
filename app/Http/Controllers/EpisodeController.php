<?php

namespace App\Http\Controllers;

use App\Http\Resources\EpisodeResource;
use App\Models\Episode;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EpisodeController extends Controller
{
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

    public function random()
    {
        return new EpisodeResource(
            $this->applyFilter()
                ->inRandomOrder()->firstOrFail()
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
}
