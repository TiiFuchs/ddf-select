<?php

namespace App\Http\Integrations\AppleMusic;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\OffsetPaginator;
use Saloon\PaginationPlugin\Paginator;
use Saloon\Traits\Plugins\AcceptsJson;

class AppleMusicConnector extends Connector implements HasPagination
{
    use AcceptsJson;

    /**
     * The Base URL of the API
     */
    public function resolveBaseUrl(): string
    {
        return 'https://api.music.apple.com/v1';
    }

    /**
     * Default headers for every request
     */
    protected function defaultHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->generateJwt(),
        ];
    }

    protected function generateJwt(): string
    {
        return Cache::remember('apple_music.jwt', config('apple_music.expires_in'), function () {
            $iat = now();
            $exp = $iat->addSeconds(config('apple_music.expires_in'));

            $payload = [
                'iss' => config('apple_music.team_id'),
                'iat' => $iat->timestamp,
                'exp' => $exp->timestamp,
            ];

            $privateKey = openssl_pkey_get_private(file_get_contents(config('apple_music.private_key_path')));

            return JWT::encode($payload, $privateKey, 'ES256', config('apple_music.key_id'));
        });
    }

    /**
     * Default HTTP client options
     */
    protected function defaultConfig(): array
    {
        return [];
    }

    public function paginate(Request $request): Paginator
    {
        return new class(connector: $this, request: $request) extends OffsetPaginator
        {
            protected ?int $perPageLimit = 25;

            protected function isLastPage(Response $response): bool
            {
                return ! $response->json('next');
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $response->dto();
            }
        };
    }
}
