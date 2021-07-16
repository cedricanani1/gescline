<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class sendUserCompteInfo extends Notification
{
    use Queueable;

    protected $details;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( $details)
    {
        //
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Compte Utilisateur Activé')
                    ->line('Votre compte d\'accès au logiciel de GESCLINE vient d\'être activé.')
                    ->line('Email: '.$notifiable->email)
                    ->line('Mot de passe: '.$this->details['password'])
                   // ->action('Pour acceder au logiciel, cliquez ici: ', url('http://scoop-coopraca.lce-test.fr/'))
                    ->line('LCE: NOUS INNOVONS, VOUS PERFORMEZ !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
