<?php declare(strict_types=1); 

namespace App\Services\Gateways;

use App\Enums\GatewayEnums\GataweyMethod;
use App\Enums\gatewayEnums\GatewayBillingType;
use App\Enums\GatewayEnums\GatewayStatus;
use App\Exceptions\ErrorInternoException;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Leopaulo88\Asaas\Facades\Asaas;

class HttpGatewayService
{
   public function criarCustumer(array $data): string
   {
        Log::info("Customer sendo criado");

        $customer = Asaas::customers()->create($data);
    
        if(!empty($customer->id) === true)
        {
            return $customer->id;
        }
            else
            {
                throw new ErrorInternoException('Error ao cadastrar cliente');
            }
 
   }

   public function criarCobranca(array $paymentData): array
   {
        Log::info("Cobrança sendo criada");

        $payment = Asaas::payments()->create($paymentData);

        // Para PIX, você pode obter o QR Code
        if(isset($payment->id) && $payment->id !== null)
        {
            if ($payment->billingType == GatewayBillingType::PIX->value) {
    
                $pixQrCode = Asaas::payments()->pixQrCode($payment->id);
    
                // Adiciona o QR Code à resposta
                $payment->pixQrCode = $pixQrCode;

            }

            return [
                'success' => true,
                'data' => $payment
            ];

        }
            else
            {
                throw new ErrorInternoException('Error ao criar cliente');
            }

   }
}