<?php

namespace App\Enums;


enum  Environment: string
{
    case LOCAL = 'local';
    case DEV = 'dev';
    case PROD = 'prod';
}
