<?php declare(strict_types=1); 

namespace App\Providers;

use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use App\Repository\Contratos\ServicoRepositoryInteface;
use App\Repository\Eloquents\EloquentAgendamentoRepository;
use App\Repository\Eloquents\EloquentAgendamentoServicoRepository;
use App\Repository\Eloquents\EloquentAuthRepository;
use App\Repository\Eloquents\EloquentBarbeiroRepository;
use App\Repository\Eloquents\EloquentClienteRepository;
use App\Repository\Eloquents\EloquentServicoRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            AgendamentosRepositoryInterface::class,
            EloquentAgendamentoRepository::class
        );

        $this->app->bind(
            ServicoRepositoryInteface::class,
            EloquentServicoRepository::class
        );

        $this->app->bind(
            AgendamentoServicoRepositoyInterface::class,
            EloquentAgendamentoServicoRepository::class
        );

        $this->app->bind(
            BarbeiroRepositoryInterface::class,
            EloquentBarbeiroRepository::class
        );

        $this->app->bind(
            ClienteRepositoryInterface::class,
            EloquentClienteRepository::class
        );

        $this->app->bind(
            AuthRepositoryInterface::class,
            EloquentAuthRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
