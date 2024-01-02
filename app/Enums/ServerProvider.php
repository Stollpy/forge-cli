<?php

namespace App\Enums;

enum ServerProvider: string
{
    case DIGITAL_OCEAN = "ocean2";
    case LINODE = "linode4";
    case VULTR = "vultr2";
    case AWS = "aws";
    case HETZNER = "hetzner";
    case CUSTOM = "custom";
}
