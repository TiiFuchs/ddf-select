<?php

namespace App\Data\AppleMusic;

use Spatie\LaravelData\Data;

class ArtworkData extends Data
{
    public function __construct(
        public ?string $bgColor,
        public int $height,
        public int $width,
        public ?string $textColor1,
        public ?string $textColor2,
        public ?string $textColor3,
        public ?string $textColor4,
        public string $url,
    ) {}

    public function imageUrl(?int $width = null, ?int $height = null)
    {
        $width ??= $this->width;
        $height ??= $this->height;

        return str_replace(['{w}', '{h}'], [$width, $height], $this->url);
    }
}
