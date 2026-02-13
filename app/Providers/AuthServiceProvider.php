<?php declare(strict_types=1); 

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Agendamento;
use App\Models\Agendamento_servico;
use App\Models\Barbearia;
use App\Models\Barbeiro;
use App\Models\Cliente;
use App\Models\Notificacao;
use App\Policy\AgendamentoPolicy;
use App\Policy\AgendamentoServicoPolicy;
use App\Policy\BarbeiroPolicy;
use App\Policy\ClientePolicy;
use App\Policy\NotificacaoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Agendamento::class => AgendamentoPolicy::class,
        Barbeiro::class => BarbeiroPolicy::class,
        Cliente::class => ClientePolicy::class,
        Notificacao::class => NotificacaoPolicy::class,
        Barbearia::class => BarbeiroPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
