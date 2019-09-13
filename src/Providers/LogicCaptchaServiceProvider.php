<?php

namespace N1ebieski\LogicCaptcha\Providers;

use Mews\Captcha\CaptchaServiceProvider;
use N1ebieski\LogicCaptcha\LogicCaptcha;

/**
 * [LogicCaptchaServiceProvider description]
 * @package N1ebieski\LogicCaptcha
 */
class LogicCaptchaServiceProvider extends CaptchaServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return null
     */
    public function boot()
    {
        parent::boot();

        // Routes
        if (strpos($this->app->version(), 'Lumen') !== false) {
            $this->app->get('captcha[/api/{config}]', 'N1ebieski\LogicCaptcha\Http\Controllers\LumenLogicCaptchaController@getCaptchaApi');
            $this->app->get('captcha[/{config}]', 'N1ebieski\LogicCaptcha\Http\Controllers\LumenLogicCaptchaController@getCaptcha');
            $this->app->get('captcha[/base64/{config}]', 'N1ebieski\LogicCaptcha\Http\Controllers\LumenLogicCaptchaController@getCaptchaBase64')
                ->name('captcha.base64');
        } else {
            if ((double)$this->app->version() >= 5.2) {
                $this->app['router']->get('captcha/api/{config?}', 'N1ebieski\LogicCaptcha\Http\Controllers\LogicCaptchaController@getCaptchaApi')->middleware('web');
                $this->app['router']->get('captcha/{config?}', 'N1ebieski\LogicCaptcha\Http\Controllers\LogicCaptchaController@getCaptcha')
                    ->middleware('web');
                $this->app['router']->get('captcha/base64/{config?}', 'N1ebieski\LogicCaptcha\Http\Controllers\LogicCaptchaController@getCaptchaBase64')
                    ->middleware('web')->name('captcha.base64');
            } else {
                $this->app['router']->get('captcha/api/{config?}', 'N1ebieski\LogicCaptcha\Http\Controllers\LogicCaptchaController@getCaptchaApi');
                $this->app['router']->get('captcha/{config?}', 'N1ebieski\LogicCaptcha\Http\Controllers\LogicCaptchaController@getCaptcha');
                $this->app['router']->get('captcha/base64/{config?}', 'N1ebieski\LogicCaptcha\Http\Controllers\LogicCaptchaController@getCaptchaBase64')
                    ->name('captcha.base64');
            }
        }

        // Publish assets
        $this->publishes([
            __DIR__ . '/../../public/js' => public_path('js/vendor/logic-captcha'),
        ], 'public');

        // Publish config
        $this->publishes([
            __DIR__ . '/../../config/logic_captcha.php' => config_path('logic_captcha.php'),
        ]);

        // Publish javascript resources
        $this->publishes([
            __DIR__ . '/../../resources/js' => resource_path('js/vendor/logic-captcha'),
        ]);

        // Publish translations
        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/logic-captcha'),
        ]);

        // Merge translations
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'logic-captcha');

        // Custom rules
        $this->app['validator']->extend('captcha', \N1ebieski\LogicCaptcha\Rules\LogicCaptcha::class);

        $this->app['validator']->extend('captcha_api', \N1ebieski\LogicCaptcha\Rules\LogicCaptchaApi::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        // Merge configs
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/logic_captcha.php', 'logic_captcha'
        );

        // Merge config with Mews\Captcha
        $this->app['config']->set([
            'captcha' => array_replace_recursive(
                $this->app['config']->get('captcha'),
                $this->app['config']->get('logic_captcha')
            )
        ]);

        $this->app->register(\N1ebieski\LogicCaptcha\Providers\LogicCaptchaEventServiceProvider::class);

        // Bind captcha
        $this->app->bind('captcha', function ($app) {
            return new LogicCaptcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
    }
}
