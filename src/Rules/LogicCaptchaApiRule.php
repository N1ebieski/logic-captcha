<?php

namespace N1ebieski\LogicCaptcha\Rules;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Validation\Rule;

class LogicCaptchaApiRule implements Rule
{
    /**
     * Captcha Key
     * @var string|array
     */
    private $key;

    /**
     * [__construct description]
     * @param Request $request [description]
     */
    public function __construct(Request $request)
    {
        $this->key = $request->get('key');
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
    public function passes($attribute, $value): bool
    {
        foreach ((array)$this->key as $key) {
            $check = Hash::check(Str::lower($value), $key);

            if ($check) {
                break;
            }
        }

        return $check ?? false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('logic-captcha::validation.failed');
    }
}
