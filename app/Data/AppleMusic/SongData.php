<?php

namespace App\Data\AppleMusic;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class SongData extends Data
{
    public function __construct(
        public string $id,
        #[MapInputName('attributes.albumName')]
        public string $albumName,
        #[MapInputName('attributes.artistName')]
        public string $artistName,
        #[MapInputName('attributes.artwork')]
        public ArtworkData $artwork,
        #[MapInputName('attributes.composerName')]
        public ?string $composerName,
        #[MapInputName('attributes.contentRating')]
        public ?string $contentRating,
        #[MapInputName('attributes.discNumber')]
        public ?int $discNumber,
        #[MapInputName('attributes.durationInMillis')]
        public ?int $durationInMillis,
        #[MapInputName('attributes.editorialNotes')]
        public ?EditorialNotesData $editorialNotes,
        /** @var string[] */
        #[MapInputName('attributes.genreNames')]
        public array $genreNames,
        #[MapInputName('attributes.hasLyrics')]
        public ?bool $hasLyrics,
        #[MapInputName('attributes.isAppleDigitalMaster')]
        public ?bool $isAppleDigitalMaster,
        #[MapInputName('attributes.isrc')]
        public ?string $isrc,
        #[MapInputName('attributes.name')]
        public string $name,
        #[MapInputName('attributes.releaseDate')]
        public ?string $releaseDate,
        #[MapInputName('attributes.trackNumber')]
        public ?int $trackNumber,
        #[MapInputName('attributes.url')]
        public string $url,
    ) {}
}
