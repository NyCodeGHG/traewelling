<?php

namespace App\Enum;

enum MapVisibility: int
{
    case PUBLIC = 0;
    case AUTHENTICATED = 1;
    case FOLLOWERS = 2;
    case ONLY_MYSELF = 3;
}
