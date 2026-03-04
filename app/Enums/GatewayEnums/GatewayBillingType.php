<?php declare(strict_types=1); 

namespace App\Enums\gatewayEnums;

enum GatewayBillingType: string
{
    CASE BOLETO = 'BOLETO';
    CASE PIX = 'PIX';
    CASE CARD_CRED = 'CARD_CRED';
    CASE UNDEFINED = 'UNDEFINED';
}