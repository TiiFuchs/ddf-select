<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        version: '1.0',
        title: 'DDF Select'
    ), security: [
        ['bearerAuth' => []],
    ]
)]
#[OA\Server('https://ddf-select.tii.one')]
#[OA\Components(
    responses: [
        new OA\Response(response: Response::HTTP_UNAUTHORIZED, description: 'Unauthorized', content: new OA\JsonContent(properties: [new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')])),
        new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Not Found', content: new OA\JsonContent(properties: [new OA\Property(property: 'message', type: 'string', example: 'Record not found.')])),
    ],
    securitySchemes: [new OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', bearerFormat: 'apiKey', scheme: 'bearer')]
)]
abstract class Controller
{
    //
}
