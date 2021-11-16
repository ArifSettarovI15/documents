<?php

namespace App\Modules\User\Notifications;

use App\Modules\User\Models\UsersHashes;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class ResetPasswordNotification extends Notification
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
        $resetPasswordurl = $this->resetPasswordUrl($notifiable);
        UsersHashes::create([
                                'user_id' =>$notifiable->getKey(),
                                'data' =>sha1($notifiable->email),
                                'hash' =>'reset_password',
                            ]);
        $data = ['verificationUrl'=>$resetPasswordurl,
                 'verificationBtn'=>'Сбросить пароль',
                 'message'=>'Для сброса пароля нажмите на кнопку ниже.'];

        return (new MailMessage)
            ->subject('Сброс пароля')
            ->line('Для сброса пароля нажмите на кнопку ниже.')
            ->markdown('User::emails.verify_email',['data'=>$data]);
    }
    protected function resetPasswordUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'forgot.change',
            Carbon::now()->addMinute(60),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->email),
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
