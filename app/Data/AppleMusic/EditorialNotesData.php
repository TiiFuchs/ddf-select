<?php

namespace App\Data\AppleMusic;

use Spatie\LaravelData\Data;

class EditorialNotesData extends Data
{
    public function __construct(
        public ?string $short,
        public ?string $standard,
        public ?string $name,
        public ?string $tagline,
    ) {}
}
