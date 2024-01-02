<?php

namespace App\Enums;

enum Database: string
{
    case MYSQL8 = "mysql8";
    case MARIADB106 = "mariadb106";
    case MARIADB1011 = "mariadb1011";
    case POSTGRES = "postgres";
    case POSTGRES13 = "postgres13";
    case POSTGRES14 = "postgres14";
    case POSTGRES15 = "postgres15";

}
