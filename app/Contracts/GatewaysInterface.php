<?php declare(strict_types=1); 

namespace App\Contracts;

interface GatewaysInterface
{
    public function processo(array $data): array;
}