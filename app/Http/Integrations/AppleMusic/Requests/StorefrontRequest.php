<?php

namespace App\Http\Integrations\AppleMusic\Requests;

use Saloon\Http\Request;

abstract class StorefrontRequest extends Request
{
    public string $storefront = 'de';

    public function getStorefront(): string
    {
        return $this->storefront;
    }

    abstract public function storefrontEndpoint(): string;

    public function resolveEndpoint(): string
    {
        return "catalog/{$this->getStorefront()}/".$this->storefrontEndpoint();
    }
}
