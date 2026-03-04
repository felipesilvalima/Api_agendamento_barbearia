<?php declare(strict_types=1); 

namespace App\Http\Controllers\Webhook;

use App\Enums\WebhookEnums\WebhookAsaas;
use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Leopaulo88\Asaas\Facades\Asaas;

class AsaasWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Log para debug (opcional)
        Log::info('Webhook recebido do Asaas', $request->all());

        $payload = $request->json()->all();
        $event = $payload['event'] ?? null;

            $paymentWebhook = match($event)
            {
                WebhookAsaas::PAYMENT_CREATED->value =>   $this->handlePayment($payload['payment'] ?? $payload,'Pagamento Criado:'),
                WebhookAsaas::PAYMENT_CONFIRMED->value => $this->handlePayment($payload['payment'] ?? $payload, 'Pagamento Confirmado:'),
                WebhookAsaas::PAYMENT_RECEIVED->value =>  $this->handlePayment($payload['payment'] ?? $payload,'Pagamento Recebido:'),
                WebhookAsaas::PAYMENT_OVERDUE->value =>   $this->handlePayment($payload['payment'] ?? $payload,'Pagamento Atrasado:'),
                WebhookAsaas::PAYMENT_REFUNDED->value =>  $this->handlePayment($payload['payment'] ?? $payload,'Pagamento Estornado:'),
                DEFAULT => ['mensagem' => 'Payment não mapeado','event' => $event]
            };
        
                return response()->json($paymentWebhook,200);
    }


    private function handlePayment(array $payment, string $mensagem): array
    {
        $payment_data = $this->payments_data($payment['id'], $mensagem);
        Log::info($mensagem, $payment_data);
        return $payment_data;
    }
    
    private function payments_data(string $payment_id, string $mensagem): array
    {
        $payment_data = Asaas::payments()->find($payment_id) ?? null;
        
       $payment = Orders::where('payment_id',$payment_data->id)->first();
       $payment->status = $payment_data->status ?? $payment->status;
       $payment->save();

           return [
                'id' => $payment_data->id,
                'status' => $payment_data->status,
                'valor' => $payment_data->value,
                'cliente' => $payment_data->customer,
                'mensagem' => $mensagem
            ];
    }
}