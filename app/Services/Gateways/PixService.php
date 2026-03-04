<?php declare(strict_types=1); 

namespace App\Services\Gateways;

use App\Contracts\GatewaysInterface;
use App\Enums\gatewayEnums\GatewayBillingType;
use App\Exceptions\ErrorInternoException;
use App\Http\ProcessamentoDTO;
use App\Models\Orders;
use App\Models\User;
use Carbon\Carbon;
use Leopaulo88\Asaas\Facades\Asaas;

class PixService extends HttpGatewayService implements GatewaysInterface
{

    public function processo(array $data): array
    {
        $user = User::find($data['user_id']);
        
        //verificar se usuário tem uma usuário na asaas
        if($user->customer_id === null)
        {
            $customer_id =  $this->criarCustumer([
                'name' => $data['name'],
                'email' => $data['email'],
                'cpfCnpj' => $data['cpfCnpj']
            ]);
            
            $user->customer_id = $customer_id;
            $user->save();
        }
       
        $cobranca = $this->criarCobranca([
            'customer' => $user->customer_id,
            'billingType' => GatewayBillingType::PIX->value,
            'value' => $data['value'],
            'dueDate' => Carbon::now()->addMinute(15),
            'description' => $validatedData['description'] ?? null,
        ]);

        
        $orders = Orders::create([
            'user_id' => $user->id,
            'payment_id' => $cobranca['data']->id,
            'value' => $data['value'],
            'status' => $cobranca['data']->status
        ]);

        if(!$orders)
        {
            throw new ErrorInternoException('error ao cadastrar pedido');
        }
        

        return $cobranca;

        
    }


    
}