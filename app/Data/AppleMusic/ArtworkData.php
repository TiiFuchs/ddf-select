<?php

namespace App\Data\AppleMusic;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Data;

#[OA\Schema(required: [
    'height', 'width', 'url',
], properties: [
    new OA\Property('bgColor', description: 'The average background color of the image.', type: 'string'),
    new OA\Property('height', description: 'The maximum height available for the image', type: 'integer'),
    new OA\Property('width', description: 'The maximum width available for the image.', type: 'integer'),
    new OA\Property('textColor1', description: 'The primary text color used if the background color gets displayed.', type: 'string'),
    new OA\Property('textColor2', description: 'The secondary text color used if the background color gets displayed.', type: 'string'),
    new OA\Property('textColor3', description: 'The tertiary text color used if the background color gets displayed.', type: 'string'),
    new OA\Property('textColor4', description: 'The final post-tertiary text color used if the background color gets displayed.', type: 'string'),
    new OA\Property('url', description: 'The URL to request the image asset. {w}x{h}must precede image filename, as placeholders for the width and height values as described above. For example, {w}x{h}bb.jpeg).', type: 'string'),
])]
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
