<?php

namespace N1ebieski\LogicCaptcha\Listeners;

/**
 * [ClearCaptchaSession description]
 */
class ClearCaptchaSession
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event) : void
    {
        session()->forget(['captcha', 'captchaId']);
    }
}
