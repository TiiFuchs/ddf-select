<?php

namespace App\Data\AppleMusic;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

/**
 * An object that represents artwork.
 */
class ArtworkData extends Data
{
    public function __construct(
        /** The average background color of the image.
         * @example 010e1a
         */
        public Optional|string $bgColor,
        /** The maximum height available for the image.
         * @example 3000
         */
        public int $height,
        /** The maximum width available for the image.
         * @example 3000
         */
        public int $width,
        /** The primary text color used if the background color gets displayed.
         * @example fefefe
         */
        public Optional|string $textColor1,
        /** The secondary text color used if the background color gets displayed.
         * @example ef9a21
         */
        public Optional|string $textColor2,
        /** The tertiary text color used if the background color gets displayed.
         * @example cbced0
         */
        public Optional|string $textColor3,
        /** The final post-tertiary text color used if the background color gets displayed.
         * @example bf7e20
         */
        public Optional|string $textColor4,
        /** The URL to request the image asset. {w}x{h}must precede image filename, as placeholders for the width and height values as described above. For example, {w}x{h}bb.jpeg).
         * @example https://is1-ssl.mzstatic.com/image/thumb/Music71/v4/4f/1b/3b/4f1b3b2e-0468-8b21-2e78-0848b94a312c/886445755954.jpg/{w}x{h}bb.jpg
         */
        public string $url,
    ) {}

    public function imageUrl(?int $width = null, ?int $height = null)
    {
        $width ??= $this->width;
        $height ??= $this->height;

        return str_replace(['{w}', '{h}'], [$width, $height], $this->url);
    }
}
