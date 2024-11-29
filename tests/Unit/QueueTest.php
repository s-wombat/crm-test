<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Jobs\ProcessWelcomeEmail;
use App\Notifications\WelcomeUserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;

class QueueTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_dispatches_job_to_queue()
    {
        // Создаем юзера
        $user = User::factory()->create();

        // Подделываем очередь
        Queue::fake();

        // Вызываем задачу
        ProcessWelcomeEmail::dispatch($user);

        // Проверяем, что задача добавлена в очередь
        Queue::assertPushed(ProcessWelcomeEmail::class, function ($job) use ($user) {
            return $job->user->id === $user->id;
        });
    }

    public function test_job_processes_successfully()
    {
        // Создаем юзера
        $user = User::factory()->create();

        // Вызываем задачу
        $job = new ProcessWelcomeEmail($user);

        // Проверяем успешное выполнение
        $this->assertNull($job->handle());
    }

    public function test_job_retries_on_failure()
    {
        Queue::fake();

        // Создаем юзера с "проблемным" email
        $user = User::factory()->create(['email' => 'fail@example.com']);

        // Вызываем задачу
        ProcessWelcomeEmail::dispatch($user);

        // Проверяем, что задача пытается повторно выполниться
        Queue::assertPushed(ProcessWelcomeEmail::class, 3); // Количество попыток можно изменить в задаче
    }

    public function test_notification_is_sent()
    {
        Notification::fake();

        $user = User::factory()->create();

        // Вызываем задачу
        ProcessWelcomeEmail::dispatch($user);

        // Проверяем, что уведомление отправлено
        Notification::assertSentTo(
            [$user],
            WelcomeUserNotification::class
        );
    }
}

