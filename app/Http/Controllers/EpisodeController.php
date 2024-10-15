<?php

namespace App\Http\Controllers;

use App\Http\Resources\EpisodeResource;
use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    public function index() {}

    public function random()
    {
        return new EpisodeResource(
            Episode::with(['tracks'])->withCount('tracks')->random()->first()
        );
    }

    public function store(Request $request) {}

    public function show($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
