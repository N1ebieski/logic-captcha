# Logic Captcha for Laravel 5

A package-plugin extends [Captcha for Laravel 5](https://github.com/mewebstudio/captcha) by Muharrem ERİN for [Laravel 5](http://www.laravel.com/).

## New features

* New option - customizable logic captcha (question -> answer1 or answer2 ...)
* Support for multiple captcha on single page by Id (only for Base64 method)
* A simple asset in jquery to reload captcha (both method - Base64 and Src by Controller)

## Installation

1. Install the package via Composer:
```
composer require n1ebieski/logic-captcha
```

2. Publish the configuration file, resources and assets via artisan:
```
php artisan vendor:publish --provider="N1ebieski\LogicCaptcha\Providers\LogicCaptchaServiceProvider"
```

## Preview
![Preview](https://i.ibb.co/s1HPTt2/preview.png)

## Configuration

`config/logic_captcha.php`

```php
return [
    'default' => [
        'math' => false,
        'logic' => true,
        'width' => 300,
        'height' => 80,
    ],

    'logic' => [
        'questions' => [
            'Color of the sky?' => [
                'blue'
            ],
            'Highest mountain on Earth?' => [
                'Mount Everest', 'MountEverest'
            ],
            'Natural enemy of the cat?' => [
                'dog', 'human', 'lol'
            ]
        ]
    ]
];
```

## Example Usage

Simple example. Of course, I suggest using the form requests, route-controller-view pattern and assets minified by webpack.

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::any('captcha-test', function(Request $request) {
    if (request()->getMethod() == 'POST') {
        $rules = ['captcha' => 'required|captcha'];
        $validator = validator()->make(request()->all(), $rules);
        if ($validator->fails()) {
            echo '<p style="color: #ff0000;">Incorrect!</p>';
        } else {
            echo '<p style="color: #00ff30;">Matched :)</p>';
        }
    }

    $form = '<p><b>Multi Captcha on one page by Base64</b></p>';

    $form .= '<form method="post" action="captcha-test">';
    $form .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    $form .= '<p><img src="' . captcha_base64(0) . '"></p>';
    $form .= '<p><input type="hidden" value="0" name="captcha_id"></p>';
    $form .= '<p><input type="text" name="captcha"></p>';
    $form .= '<p><button type="submit">Check</button>
              <button class="reload_captcha_base64" type="button"
              data-route="' . route('captcha.base64', ['default']) . '">Reload</button></p>';
    $form .= '</form>';

    $form .= '<form method="post" action="captcha-test">';
    $form .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    $form .= '<p><img src="' . captcha_base64(1) . '"></p>';
    $form .= '<p><input type="hidden" value="1" name="captcha_id"></p>';
    $form .= '<p><input type="text" name="captcha"></p>';
    $form .= '<p><button type="submit">Check</button>
              <button class="reload_captcha_base64" type="button"
              data-route="' . route('captcha.base64', ['default']) . '">Reload</button></p>';
    $form .= '</form>';

    $form .= '<p><b>Single Captcha by Async Src Controller</b></p>';

    $form .= '<form method="post" action="captcha-test">';
    $form .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    $form .= '<p>' . captcha_img() . '</p>';
    $form .= '<p><input type="text" name="captcha"></p>';
    $form .= '<p><button type="submit">Check</button>
              <button class="reload_captcha_img" type="button">Reload</button></p>';
    $form .= '</form>';

    $form .= '<script
	     src="https://code.jquery.com/jquery-3.4.1.min.js"
	     integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
	     crossorigin="anonymous"></script>';

    $form .= '<script src="' . asset('js/vendor/logic-captcha/captcha_reload.js') . '"></script>';

    return $form;
});
```

## Copyright and License

Base package [Captcha for Laravel 5](https://github.com/mewebstudio/captcha) was written by [Muharrem ERİN](https://github.com/mewebstudio).

Package [Logic Captcha for Laravel 5](https://github.com/N1ebieski/laravel-logic-captcha) was written by [Mariusz Wysokiński](https://github.com/N1ebieski) and is released under the MIT License.
