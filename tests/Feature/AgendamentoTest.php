<?php declare(strict_types=1); 

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Barbearia;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AgendamentoTest extends TestCase
{
    /**
     * A basic feature test example.
     */
     public function test_criar_agendamento()
    {
       $user = User::factory()->create([
    'barbearia_id' => 33,
    'status' => 'ATIVO',
    'role' => 'cliente' // exatamente como sua regra espera
]);

    // Gera token JWT real
    $token = auth()->login($user);

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/v2/agendamentos', [
            'id_barbeiro' => 4,
            'data' => '2026-03-08',
            'hora' => '19:20:00',
            'servicos' => [1,2,3]
        ]);

    $response->assertStatus(201);
    
    }
}
