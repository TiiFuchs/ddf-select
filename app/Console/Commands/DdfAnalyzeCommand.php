<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Episode;
use App\Models\Scopes\NotIgnoredScope;
use App\Models\Track;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class DdfAnalyzeCommand extends Command
{
    protected $signature = 'ddf:analyze';

    protected $description = 'Analyzes albums and tracks to create episodes';

    public function handle(): void
    {
        $this->removeNewlyIgnoredAlbums();

        $albums = Album::with('tracks')
            ->whereAnalyzed(false)
            ->get();

        foreach ($albums as $album) {
            $this->analyzeAlbum($album);
        }
    }

    protected function analyzeAlbum(Album $album)
    {
        static $i = 0;

        /** @var Collection<Track> $tracks */
        $tracks = $album->tracks
            ->filter(fn (Track $track) => ! str($track->name)->contains(['(Inhaltsangabe)', '(Outro)']))
            ->sortBy(['disc_number', 'track_number']);

        $subEpisodes = $this->splitTracks($tracks);
        $isSubEpisode = count($subEpisodes) > 1;

        foreach ($subEpisodes as $subEpisodeTracks) {
            $this->importEpisode($album, $subEpisodeTracks, $isSubEpisode);
        }
    }

    /**
     * @param  Collection<Track>  $tracks
     * @return array<Collection<Track>>
     */
    protected function splitTracks(Collection $tracks): array
    {
        $subEpisodes = [];
        $subEpisode = new Collection;
        $hasNumberedParts = false;

        foreach ($tracks as $track) {
            [$episodeNumber, $episodeName, $partType, $partNumber] = $this->splitTrackName($track->name);

            if ($subEpisode->isNotEmpty() && $hasNumberedParts) {
                if ($partType === 'Prolog' || $partType === 'Titelmusik' || (int) $partNumber === 1) {

                    // For episodes like "150 A"
                    if ($episodeNumber && ! is_numeric($episodeNumber)) {
                        continue;
                    }

                    $subEpisodes[] = $subEpisode;
                    $subEpisode = new Collection;
                    $hasNumberedParts = false;
                }
            }

            $subEpisode->add($track);

            if (is_numeric($partNumber)) {
                $hasNumberedParts = true;
            }
        }
        $subEpisodes[] = $subEpisode;

        return $subEpisodes;
    }

    protected function parseAlbumName(string $name): array
    {
        preg_match('/(?:Folge (\d+): )?(.+)/i', $name, $matches);
        [$_, $episodeNumber, $episodeTitle] = $matches + array_fill(0, 3, null);

        return [$episodeNumber, $episodeTitle];
    }

    protected function splitTrackName(string $name): array
    {
        preg_match('/^(?:([\d\w ]+) - )?(.+?)(?: \((\w+|Teil (\d+))\))?$/i', $name, $matches);
        [$_, $episodeNumber, $episodeName, $partType, $partNumber] = $matches + array_fill(0, 5, null);

        return [$episodeNumber, $episodeName, $partType, $partNumber];
    }

    /**
     * @param  Collection<Track>  $tracks
     * @return void
     */
    protected function importEpisode(Album $album, Collection $tracks, bool $isSubEpisode = false)
    {
        [$episodeNumber, $episodeName] = $this->parseAlbumName($album->name);
        [$subEpisodeNumber, $subEpisodeName, $partType, $partNumber] = $this->splitTrackName($tracks[0]->name);
        $subEpisodeName = str($subEpisodeName)->after('Die drei ??? ')->toString();

        $number = (int) $episodeNumber ?: null;
        $name = $isSubEpisode ? $subEpisodeName : $episodeName;

        $duration = $tracks->pluck('duration_in_millis')->sum();

        $episode = Episode::create([
            'number' => $number,
            'name' => $name,
            'duration_in_millis' => $duration,
            'release_date' => $album->release_date,
            'album_id' => $album->id,
        ]);

        $episode->tracks()->sync($tracks->pluck('id'));

        $album->update([
            'analyzed' => true,
        ]);
    }

    protected function removeNewlyIgnoredAlbums()
    {
        $albums = Album::withoutGlobalScope(NotIgnoredScope::class)
            ->whereIgnore(true)->has('episodes')->get();

        $albums->each(fn (Album $album) => $album->episodes()->delete());
    }
}
