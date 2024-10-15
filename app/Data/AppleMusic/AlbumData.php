<?php

namespace App\Data\AppleMusic;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class AlbumData extends Data
{
    public function __construct(
        public string $id,
        #[MapInputName('attributes.artistName')]
        public string $artistName,
        #[MapInputName('attributes.artwork')]
        public ArtworkData $artwork,
        #[MapInputName('attributes.contentRating')]
        public ?string $contentRating,
        #[MapInputName('attributes.copyright')]
        public ?string $copyright,
        #[MapInputName('attributes.editorialNotes')]
        public ?EditorialNotesData $editorialNotes,
        /** @var string[] */
        #[MapInputName('attributes.genreNames')]
        public array $genreNames,
        #[MapInputName('attributes.isCompilation')]
        public bool $isCompilation,
        #[MapInputName('attributes.isComplete')]
        public bool $isComplete,
        #[MapInputName('attributes.isMasteredForItunes')]
        public bool $isMasteredForItunes,
        #[MapInputName('attributes.isSingle')]
        public bool $isSingle,
        #[MapInputName('attributes.name')]
        public string $name,
        #[MapInputName('attributes.recordLabel')]
        public ?string $recordLabel,
        #[MapInputName('attributes.releaseDate')]
        public ?string $releaseDate,
        #[MapInputName('attributes.trackCount')]
        public int $trackCount,
        #[MapInputName('attributes.upc')]
        public ?string $upc,
        #[MapInputName('attributes.url')]
        public string $url,
        /** @var SongData[] */
        #[MapInputName('relationships.tracks.data')]
        public array $tracks,
    ) {}
}
