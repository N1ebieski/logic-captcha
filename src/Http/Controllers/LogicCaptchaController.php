<?php

namespace N1ebieski\LogicCaptcha\Http\Controllers;

use Illuminate\Http\JsonResponse;

use Illuminate\Routing\Controller;
use N1ebieski\LogicCaptcha\LogicCaptcha;
use N1ebieski\LogicCaptcha\Http\Requests\LogicCaptcha\CaptchaRequest;

/**
 * [LogicCaptchaController description]
 */
class LogicCaptchaController extends Controller
{
    /**
     * [getCaptcha description]
     * @param  LogicCaptcha $captcha [description]
     * @param  string       $config  [description]
     * @param  CaptchaRequest $request [description]
     * @return [type]                [description]
     */
    public function getCaptcha(LogicCaptcha $captcha, string $config = 'default', CaptchaRequest $request)
    {
        if (ob_get_contents()) {
            ob_clean();
        }

        return $captcha->setId($request->get('captcha_id'))->create($config);
    }

    /**
     * [getCaptchaBase64 description]
     * @param  LogicCaptcha   $captcha [description]
     * @param  string         $config  [description]
     * @param  CaptchaRequest $request [description]
     * @return JsonResponse            [description]
     */
    public function getCaptchaBase64(LogicCaptcha $captcha, string $config = 'default', CaptchaRequest $request) : JsonResponse
    {
        $output = app('captcha')->setId($request->get('captcha_id'))->create($config, true);

        return response()->json(['img' => $output['img']]);
    }

    /**
     * [getCaptchaApi description]
     * @param  LogicCaptcha $captcha [description]
     * @param  string       $config  [description]
     * @return [type]                [description]
     */
    public function getCaptchaApi(LogicCaptcha $captcha, string $config = 'default')
    {
        return $captcha->create($config, true);
    }
}
