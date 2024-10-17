<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\OpenApi(info: new OA\Info(version: '1.0', title: 'DDF Select'), security: [['bearerAuth' => []]])]
#[OA\Server('https://ddf-select.tii.one')]
#[OA\Components(securitySchemes: [new OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', bearerFormat: 'apiKey', scheme: 'bearer')])]
abstract class Controller
{
    //
}
