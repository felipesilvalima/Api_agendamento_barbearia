<?php declare(strict_types=1); 

namespace App\Http\Controllers\pagamento;

use App\Contracts\GatewaysInterface;
use Symfony\Component\HttpFoundation\Request;

class PagamentoController
{
    public function processamento(Request $request)
    {
        $gateway = app()->make(GatewaysInterface::class, 
            $request->all()
        );
        
        $processamentoGateway = $gateway->processo([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'cpfCnpj' => $request->input('cpfCnpj'),
            'value' => $request->input('value'),
            'billingType' => $request->input('billingType'),
            'user_id' => $request->input('user_id')
        ]); 

        return response()->json($processamentoGateway);
    }
}