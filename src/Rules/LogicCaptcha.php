<?php

namespace N1ebieski\LogicCaptcha\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use N1ebieski\LogicCaptcha\Events\CaptchaValidateDone;

/**
 * [Recaptcha_v2 description]
 */
class LogicCaptcha implements Rule
{
    /**
     * Captcha Id
     * @var string
     */
    private $id;

    /**
     * [__construct description]
     * @param Request $request [description]
     */
    public function __construct(Request $request)
    {
        $this->id = $request->get('captcha_id');
    }

    /**
     * [validate description]
     * @param  [type] $attribute  [description]
     * @param  [type] $value      [description]
     * @param  [type] $parameters [description]
     * @param  [type] $validator  [description]
     * @return [type]             [description]
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->passes($attribute, $value);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        if (!session()->has('captcha')) {
            return false;
        }

        $keys = !is_null($this->id) && session()->has('captchaId.' . $this->id . '.key')
            ? session()->get('captchaId.' . $this->id . '.key')
            : session()->get('captcha.key');

        foreach ((array)$keys as $key) {
            $check = Hash::check(Str::lower($value), $key);

            if ($check) break;
        }

        event(new CaptchaValidateDone);

        return $check ?? false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('logic_captcha::validation.failed');
    }
}
