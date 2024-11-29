<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\WelcomeUserNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * Create a new job instance.
     */
    public function __construct(
        public User $user
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->user->email === 'fail@example.com') {
            throw new \Exception('Test failure');
        }

        $this->user->notify(new WelcomeUserNotification($this->user));
    }

    /**
     * Обработка сбоя при выполнении задачи.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(?Throwable $exception): void
    {
        // Логирование ошибки
        Log::error('Ошибка отправки письма: ' . $exception->getMessage());
    }
}
