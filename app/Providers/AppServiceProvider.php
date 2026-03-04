<?php declare(strict_types=1); 

namespace App\Providers;

use App\Contracts\GatewaysInterface;
use App\Enums\GatewayEnums\GatewayBillingType;
use App\Repository\Contratos\AgendamentoServicoRepositoyInterface;
use App\Repository\Contratos\AgendamentosRepositoryInterface;
use App\Repository\Contratos\AuthRepositoryInterface;
use App\Repository\Contratos\BarbeariaInterfaceRepository;
use App\Repository\Contratos\BarbeiroRepositoryInterface;
use App\Repository\Contratos\ClienteRepositoryInterface;
use App\Repository\Contratos\NotificacaoRepositoryInterface;
use App\Repository\Contratos\ServicoRepositoryInteface;
use App\Repository\Eloquents\EloquentAgendamentoRepository;
use App\Repository\Eloquents\EloquentAgendamentoServicoRepository;
use App\Repository\Eloquents\EloquentAuthRepository;
use App\Repository\Eloquents\EloquentBarbeariaRepository;
use App\Repository\Eloquents\EloquentBarbeiroRepository;
use App\Repository\Eloquents\EloquentClienteRepository;
use App\Repository\Eloquents\EloquentServicoRepository;
use App\Repository\Eloquents\EloquentNotificaoRepository;
use App\Services\Gateways\BoletoService;
use App\Services\Gateways\CardService;
use App\Services\Gateways\PixService;
use ErrorException;
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

        $this->app->bind(
            NotificacaoRepositoryInterface::class,
            EloquentNotificaoRepository::class
        );

        $this->app->bind(
            BarbeariaInterfaceRepository::class,
            EloquentBarbeariaRepository::class
        );

        $this->app->bind(
            GatewaysInterface::class,
            function($app, $params)
            {
                $gatway = empty($params['gateway']) === false ? $params['gateway'] : false;
                $billingType = empty($params['billingType']) === false ? $params['billingType'] : false;
                
                    if($gatway === false && $billingType === false)
                    {
                        throw new ErrorException("Error na chamada de método de pagamento");
                    }
                        $classes = [
                            'ASAAS' =>[
                                GatewayBillingType::BOLETO->value => BoletoService::class,
                                GatewayBillingType::CARD_CRED->value => CardService::class,
                                GatewayBillingType::PIX->value => PixService::class
                            ]
                        ];
                            
                            return new $classes[$gatway][$billingType];
            });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
