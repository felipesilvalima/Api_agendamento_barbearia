<?php declare(strict_types=1); 

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Agendamento;
use App\Policy\AgendamentoPolicy;
use App\Policy\AgendamentoServicoPolicy;
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
    Agendamento::class => AgendamentoServicoPolicy::class

    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
