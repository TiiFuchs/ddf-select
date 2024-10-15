<?php

namespace App\Http\Integrations\AppleMusic\Requests;

use App\Data\AppleMusic\AlbumData;
use Saloon\Enums\Method;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetArtistsAlbums extends StorefrontRequest implements Paginatable
{
    protected ?string $include;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function storefrontEndpoint(): string
    {
        return "artists/{$this->artistId}/albums";
    }

    protected function defaultQuery(): array
    {
        return [
            'include' => $this->include,
        ];
    }

    public function createDtoFromResponse(Response $response): array
    {
        $albumData = $response->json('data');

        return AlbumData::collect($albumData);
    }

    public function include(string|array $include): static
    {
        if (is_array($include)) {
            $include = implode(',', $include);
        }

        $this->include = $include;

        return $this;
    }

    public function __construct(
        public string $artistId
    ) {}
}
