<?php declare(strict_types=1); 

namespace App\Services\Gateways;

use App\Contracts\GatewaysInterface;
use App\Enums\GatewayEnums\GataweyMethod;
use App\Enums\GatewayEnums\GatewayStatus;
use App\Services\Gateways\HttpGatewayService;
use Carbon\Carbon;
use Exception;
use League\CommonMark\Node\NodeWalker;

class BoletoService extends  HttpGatewayService implements GatewaysInterface
{

    public function processo(array $data): array
    {
        return ['gateway tipo boleto'];
    }
}