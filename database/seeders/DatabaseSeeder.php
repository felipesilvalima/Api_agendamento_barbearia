<?php declare(strict_types=1); 

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //\App\Models\User::factory(5)->create();

        // Criar 10 clientes com seus usuários e barbearias
        //\App\Models\Cliente::factory(10)->create();

        // Criar 5 barbeiros
        //\App\Models\Barbeiro::factory(5)->create();

        // Criar 20 agendamentos
        //\App\Models\Agendamento::factory(20)->create();

        // Criar serviços
        //\App\Models\Servico::factory(10)->create();

        // Criar relacionamentos agendamento-serviço
        \App\Models\AgendamentoServico::factory(30)->create();


        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
