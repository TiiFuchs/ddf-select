<?php

namespace App\Models;

enum EpisodeDuration: string
{
    case Short = 'short';

    case Normal = 'normal';

    case Long = 'long';
}
