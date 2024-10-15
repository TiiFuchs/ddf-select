<?php

namespace App\Http\Integrations\AppleMusic\Requests;

use Saloon\Enums\Method;

class GetArtist extends StorefrontRequest
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function storefrontEndpoint(): string
    {
        return "artists/{$this->id}";
    }

    public function __construct(
        public readonly int $id,
    ) {}
}
