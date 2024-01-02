<?php

namespace App\Enums;

enum ServerType: string
{
    case APP = "app";
    case WEB = "web";
    case LOADBALANCER = "loadbalancer";
    case DATABASE = "database";
    case WORKER = "worker";
    case MEILISEARCH = "meilisearch";
}
