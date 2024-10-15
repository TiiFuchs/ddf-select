<?php

return [

    'ddf_artist_id' => env('DDF_ARTIST_ID'),

    'private_key_path' => storage_path('app/private/'.env('APPLE_MUSIC_KEY_FILE')),

    'key_id' => env('APPLE_MUSIC_KEY_ID'),

    'team_id' => env('APPLE_MUSIC_TEAM_ID'),

    'expires_in' => env('DDF_JWT_EXPIRES_IN', 600), // 10 minutes

];
