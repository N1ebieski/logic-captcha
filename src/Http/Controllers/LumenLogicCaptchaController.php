<?php

namespace N1ebieski\LogicCaptcha\Http\Controllers;

use Illuminate\Http\JsonResponse;

use Laravel\Lumen\Routing\Controller;
use N1ebieski\LogicCaptcha\LogicCaptcha;
use N1ebieski\LogicCaptcha\Http\Requests\LogicCaptcha\CaptchaRequest;

/**
 * [LumenLogicCaptchaController description]
 */
class LumenLogicCaptchaController extends Controller
{

    /**
     * [getCaptcha description]
     * @param  LogicCaptcha   $captcha [description]
     * @param  string         $config  [description]
     * @param  CaptchaRequest $request [description]
     * @return [type]                  [description]
     */
    public function getCaptcha(LogicCaptcha $captcha, string $config = 'default', CaptchaRequest $request)
    {
        return $captcha->setId($request->get('captcha_id'))->create($config);
    }

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
