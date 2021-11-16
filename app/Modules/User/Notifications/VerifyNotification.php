<?php

namespace App\Modules\User\Notifications;

use App\Models\User;
use App\Modules\User\Models\UsersHashes;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;

use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

use URL;


class VerifyNotification extends VerifyEmail
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $verificationUrl = $this->verificationUrl($notifiable);
        UsersHashes::create([
            'user_id' =>$notifiable->getKey(),
            'data' =>sha1($notifiable->getEmailForVerification()),
            'hash' =>'verify_notification',
            ]);
        $data = ['verificationUrl'=>$verificationUrl,
                 'verificationBtn'=>'Подтвердить',
                 'message'=>'Для подтверждения адреса электронной почты нажмите на кнопку ниже.'];

        return (new MailMessage)
            ->subject('Подтверждение адреса электронной почты')
            ->line('Для подтверждения адреса электронной почты нажмите на кнопку ниже.')
            ->action('Подтвердить', $verificationUrl)
            ->markdown('User::emails.verify_email',['data'=>$data]);
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinute(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
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
