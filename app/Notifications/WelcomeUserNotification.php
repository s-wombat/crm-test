<?php

namespace App\Notifications;

use Exception;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class WelcomeUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Максимальное количество попыток.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Таймаут между попытками (в секундах).
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public User $user
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Добро пожаловать в нашу систему!')
                    ->greeting("Здравствуйте, {$this->user->name}!")
                    ->line('Мы рады приветствовать вас в нашей CRM системе.')
                    ->line('Если у вас есть вопросы, свяжитесь с нами.')
                    ->action('Посетить сайт', url('/'))
                    ->line('Спасибо, что выбрали нас!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    /**
     * Обработка сбоя при выполнении задачи.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Логирование ошибки
        Log::error('Ошибка отправки письма: ' . $exception->getMessage());
    }

}
