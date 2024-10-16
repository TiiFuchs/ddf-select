<?php

namespace App\Http\Controllers;

use App\Http\Requests\EpisodeFilterRequest;
use App\Http\Resources\EpisodeResource;
use App\Models\Episode;
use Illuminate\Database\Eloquent\Builder;

class EpisodeController extends Controller
{
    protected function applyFilter(EpisodeFilterRequest $request)
    {
        return Episode::query()
            ->when($request->duration, fn (Builder $query, string $duration) => $query->duration($duration))
            ->when($request->with, fn (Builder $query, array $with) => $query->with($with));
    }

    public function index(EpisodeFilterRequest $request)
    {
        return EpisodeResource::collection(
            $this->applyFilter($request)
                ->simplePaginate(5)
        );
    }

    public function random(EpisodeFilterRequest $request)
    {
        return new EpisodeResource(
            $this->applyFilter($request)
                ->random()->first()
        );
    }

    public function show($id)
    {
        return new EpisodeResource(
            Episode::with(['album', 'tracks'])->find($id),
        );
    }
}
