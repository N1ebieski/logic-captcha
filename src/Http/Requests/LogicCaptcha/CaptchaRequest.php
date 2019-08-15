<?php

namespace N1ebieski\LogicCaptcha\Http\Requests\LogicCaptcha;

use Illuminate\Foundation\Http\FormRequest;

class CaptchaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('captcha_id') && empty($this->get('captcha_id'))) {
            $this->merge([
                'captcha_id' => null
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'captcha_id' => 'nullable|integer'
        ];
    }
}
