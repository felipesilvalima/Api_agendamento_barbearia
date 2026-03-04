<?php declare(strict_types=1); 

namespace App\Enums\GatewayEnums;

enum GatewayStatus: int
{
    CASE SUCESSO = 200;
    CASE ERROR = 400;
    CASE UNAUTHORIZED = 401;
    CASE FORBIDDEN = 403;
    CASE NOT_FUND = 404;
    CASE SERVER_ERROR = 500;
}