<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Listeners\SendWelcomeNotification;
use App\Events\ForgotPassword;
use App\Listeners\SendForgotPassword;
use App\Events\ResetedPassword;
use App\Listeners\SendResetedPassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        UserRegistered::class => [
					SendWelcomeNotification::class,
				],
				ForgotPassword::class => [
					SendForgotPassword::class,
				],
				ResetedPassword::class => [
					SendResetedPassword::class,
				],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
