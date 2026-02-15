<?php declare(strict_types=1); 

namespace App\Helpers;

trait AgendamentoConfig
{
    public function regras (): array
    {
        return [

            'atributos_agendamento' => [
                'atributos' => ['id','data','hora','status','id_barbeiro','id_cliente','barbearia_id'],
                'filtro_validado' => 'filtro_agendamento_validado',                    
                'filtro' => 'filtro_agendamento',                    
            ],
            
            'atributos_cliente' => [
                'atributos' => ['id','user_id','telefone','data_cadastro','status','barbearia_id'],
                'filtro_validado' => 'filtro_cliente_validado',                    
                'filtro' => 'filtro_cliente',                    
            ],

            'atributos_barbeiro' => [
                'atributos' => ['id','user_id','telefone','status','especialidade','barbearia_id'],
                'filtro_validado' => 'filtro_barbeiro_validado',                  
                'filtro' => 'filtro_barbeiro',                  
            ],

            'atributos_servico' => [
                'atributos' => ['id','nome','descricao','duracao_minutos','preco','barbearia_id'],
                'filtro_validado' => 'filtro_servico_validado',                    
                'filtro' => 'filtro_servico',                    
            ]

        ];
    }
}