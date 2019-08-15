<?php

if (!function_exists('captcha_base64')) {

    function captcha_base64(int $id = null, string $config = 'default')
    {
        $output = app('captcha')->setId($id)->create($config, true);

        return $output['img'];
    }
}
