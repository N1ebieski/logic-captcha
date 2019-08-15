<?php

namespace N1ebieski\LogicCaptcha\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * [EventServiceProvider description]
 */
class LogicCaptchaEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \N1ebieski\LogicCaptcha\Events\CaptchaValidateDone::class => [
            \N1ebieski\LogicCaptcha\Listeners\ClearCaptchaSession::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
