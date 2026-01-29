<?php declare(strict_types=1); 

namespace App\Notifications;

use App\Models\Agendamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusAlteradoNotificacao extends Notification implements ShouldQueue

{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Agendamento $agendamento, public ?string $reagendado = null)
    { 
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database']; //'broadcast' utilizando websockt em tempo real
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->reagendado === null ? $this->agendamento->status : $this->reagendado;

        return  match($status){
                "AGENDADO" => $this->agendado(),
                "CONCLUIDO" => $this->concluido(),
                "CANCELADO" => $this->cancelado(),
                "REAGENDADO" => $this->reagendado(),
            };
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $status = $this->reagendado === null ? $this->agendamento->status : $this->reagendado;

        return match($status) {

            "AGENDADO" => [
                "mensagem" => "Novo Agendamento",
                "cliente" => $this->agendamento->cliente->nome
            ],

            "CONCLUIDO" => [
                "mensagem" => "seu agendamento foi Concluido com sucesso",
                "numero_agendamento" => $this->agendamento->id
            ],

            "CANCELADO" => [
                "mensagem" => "seu agendamento foi Cancelado",
                "numero_agendamento" => $this->agendamento->id
            ],

            "REAGENDADO" => [
                "mensagem" => "seu agendamento foi Reagendado",
                "numero_agendamento" => $this->agendamento->id
            ],
        };
    }

    public function agendado()
    {
       return (new MailMessage)
                    ->line('novo Agendamento.')
                    ->action('Você possuir um novo agendamento na sua agenda', url('/agendamentos'))
                    ->line('Obrigado por usar nosso sistema!');
    }

    public function concluido()
    {
       return (new MailMessage)
                    ->line('Agendamento Concluido.')
                    ->action('O seu agendamento foi concluido com sucesso', url('/agendamentos'))
                    ->line('Obrigado por usar nosso sistema!');
    }

    public function cancelado()
    {
       return (new MailMessage)
                    ->line('Agendamento Cancelado.')
                    ->action('Você possuir um agendamento cancelado', url('/agendamentos'))
                    ->line('Obrigado por usar nosso sistema!');
    }

    public function reagendado()
    {
       return (new MailMessage)
                    ->line('Agendamento Reagendado.')
                    ->action('Você possuir um agendamento reagendado', url('/agendamentos'))
                    ->line('Obrigado por usar nosso sistema!');
    }

    // public function toBroadcast(object $notifiable): BroadcastMessage
    // {
    //     return new BroadcastMessage([
    //         'mensagem' => 'Seu agendamento foi concluído com sucesso'
    //     ]);
    // }
}
