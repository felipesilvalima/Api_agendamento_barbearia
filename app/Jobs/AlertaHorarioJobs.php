<?php declare(strict_types=1); 

namespace App\Jobs;

use App\Models\Agendamento;
use App\Notifications\Alertas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AlertaHorarioJobs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    //rodar o comando 
    //php artisan queue:work

    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Agendamento::whereBetween('hora', [
            now()->addMinutes(10),
            now()->addMinutes(11),
        ])->each(function ($agendamento) {

            Log::info('Alerta de horário.', [
                'id' => $agendamento->id,
                'user_id' => auth('api')->user()->id
            ]);

            $agendamento->cliente->user->notify(
                new Alertas($agendamento)
            );
        });
    }
}
