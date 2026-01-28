<?php declare(strict_types=1); 

namespace App\Listeners;

use App\Events\StatusAlterado;
use App\Models\User;
use App\Notifications\Alertas;
use App\Notifications\StatusAlteradoNotificacao;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnviarNotificacaoStatus
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(StatusAlterado $event): void
    {
        $event->agendamento->cliente->user->notify(
            new StatusAlteradoNotificacao($event->agendamento, $event->reagendamento)
        );
    }
}
