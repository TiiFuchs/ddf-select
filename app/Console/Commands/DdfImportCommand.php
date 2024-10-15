<?php

namespace App\Console\Commands;

use App\Data\AppleMusic\AlbumData;
use App\Data\AppleMusic\SongData;
use App\Http\Integrations\AppleMusic\AppleMusicConnector;
use App\Http\Integrations\AppleMusic\Requests\GetArtistsAlbums;
use App\Models\Album;
use App\Models\Track;
use Illuminate\Console\Command;

use function Laravel\Prompts\spin;

class DdfImportCommand extends Command
{
    protected $signature = 'ddf:import';

    protected $description = 'Imports albums from Apple Music';

    public int $newlyCreatedAlbums = 0;

    public function handle(): void
    {
        $appleMusic = new AppleMusicConnector;

        $albums = $appleMusic->paginate(
            (new GetArtistsAlbums(config('apple_music.ddf_album_id')))
                ->include('tracks')
        )->setPerPageLimit(100)->items();

        spin(function () use ($albums) {
            foreach ($albums as $album) {
                $this->importAlbum($album);
            }
        }, 'Importing albums...');

        \Laravel\Prompts\info("{$this->newlyCreatedAlbums} albums were imported");
    }

    protected function importAlbum(AlbumData $albumData): ?Album
    {
        if (collect($albumData->tracks)->whereNull('durationInMillis')->count() > 0) {
            return null;
        }

        $album = Album::updateOrCreate([
            'am_id' => $albumData->id,
        ], [
            'name' => $albumData->name,
            'track_count' => $albumData->trackCount,
            'release_date' => $albumData->releaseDate,
            'url' => $albumData->url,
            'artwork' => $albumData->artwork,
        ]);

        if ($album->wasRecentlyCreated) {
            $this->newlyCreatedAlbums++;
        }

        foreach ($albumData->tracks as $trackData) {
            $this->importTrack($album, $trackData);
        }

        return $album;
    }

    protected function importTrack(Album $album, SongData $trackData): Track
    {
        return $album->tracks()->updateOrCreate([
            'am_id' => $trackData->id,
        ], [
            'name' => $trackData->name,
            'disc_number' => $trackData->discNumber,
            'track_number' => $trackData->trackNumber,
            'duration_in_millis' => $trackData->durationInMillis,
        ]);
    }
}
