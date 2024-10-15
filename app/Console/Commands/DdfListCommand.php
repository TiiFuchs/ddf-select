<?php

namespace App\Console\Commands;

use App\Models\Episode;
use Illuminate\Console\Command;

use function Laravel\Prompts\table;

class DdfListCommand extends Command
{
    protected $signature = 'ddf:list';

    protected $description = 'Command description';

    public function handle(): void
    {
        $episodes = Episode::with('album')
            ->orderBy('duration_in_millis')->get();

        table(['#', 'Name', 'Album', 'Dauer'], $episodes->map(fn (Episode $episode) => [$episode->number, $episode->name, $episode->album->name, $episode->durationFormatted()]));
    }
}
