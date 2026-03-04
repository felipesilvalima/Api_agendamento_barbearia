<?php declare(strict_types=1); 

namespace App\Services\Gateways;

use App\Contracts\GatewaysInterface;

class CardService implements GatewaysInterface
{
    public function processo(array $data): array
    {
        return ['gateway do tipo cartão'];
    }
}