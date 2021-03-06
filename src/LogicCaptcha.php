<?php

namespace N1ebieski\LogicCaptcha;

use Mews\Captcha\Captcha;

/**
 * [LogicCaptcha description]
 * @package N1ebieski/LogicCaptcha
 */
class LogicCaptcha extends Captcha
{
    /**
     * [protected description]
     * @var bool
     */
    protected $logic = true;

    /**
     * [protected description]
     * @var array
     */
    protected $questions;

    /**
     * [protected description]
     * @var int
     */
    protected $id;

    /**
     * @param int|null $id
     *
     * @return static
     */
    public function setId(int $id = null)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * [isLogic description]
     * @return bool [description]
     */
    private function isLogic() : bool
    {
        return $this->logic === true && $this->math === false;
    }

    /**
     * Writing captcha text
     */
    protected function text() : void
    {
        if ($this->isLogic()) {
            $words = (array)explode(' ', $this->text);

            $this->textLeftPadding = 10;
            $this->length = count($words);

            $marginTop = $this->image->height() / count($words);

            foreach ($words as $key => $char) {
                $marginLeft = $this->textLeftPadding + ($key * ($this->image->width() - $this->textLeftPadding) / $this->length);

                $this->image->text($char, $marginLeft, $marginTop, function ($font) {
                    $font->file($this->font());
                    $font->size($this->fontSize());
                    $font->color($this->fontColor());
                    $font->align('left');
                    $font->valign('top');
                    $font->angle($this->angle());
                });
            }
        } else {
            parent::text();
        }
    }

    /**
     * Generate captcha text
     *
     * @return array
     */
    protected function generate() : array
    {
        if ($this->isLogic()) {
            $config = $this->generateLogic();
        } else {
            $config = parent::generate();
        }

        $this->addIdToSession(json_decode($config['key']));

        return $config;
    }

    /**
     * [addIdToSession description]
     * @param array|string $key [description]
     */
    protected function addIdToSession($key) : void
    {
        if (!is_null($this->id)) {
            $this->session->put('captchaId.' . $this->id, [
                'sensitive' => $this->sensitive,
                'key' => $key
            ]);
        }
    }

    /**
     * [addToSession description]
     * @param array|string $key [description]
     */
    protected function addToSession($key) : void
    {
        $this->session->put('captcha', [
            'sensitive' => $this->sensitive,
            'key' => $key
        ]);
    }

    /**
     * Generate captcha logic text
     *
     * @return array [description]
     */
    protected function generateLogic() : array
    {
        $this->configure('logic');

        if (!is_array($this->questions)) {
            throw new \N1ebieski\LogicCaptcha\Exceptions\NotArray(
                'Questions must be array.'
            );
        }

        if (count($this->questions) === 0) {
            throw new \N1ebieski\LogicCaptcha\Exceptions\NotCountable(
                'Questions must be countable.'
            );
        }

        $question = array_rand($this->questions);
        $answers = (array)$this->questions[$question];

        if (count($answers) === 0) {
            throw new \N1ebieski\LogicCaptcha\Exceptions\NotCountable(
                'Answers must be countable.'
            );
        }

        $hash = [];
        foreach ($answers as $answer) {
            $hash[] = $this->hasher->make(
                $this->str->lower($answer)
            );
        }

        $this->addToSession($hash);

        return [
            'value' => (string)$question,
            'sensitive' => $this->sensitive,
            'key' => json_encode($hash)
        ];
    }

    /**
     * Undocumented function
     *
     * @param string $config
     * @param boolean $api
     * @return void
     */
    public function create(string $config = 'default', bool $api = false)
    {
        $create = parent::create($config, $api);

        if (is_array($create)) {
            $create['key'] = json_decode($create['key']);
        }

        return $create;
    }

    /**
     * Random font size
     *
     * @return int [description]
     */
    protected function fontSize() : int
    {
        if ($this->isLogic()) {
            return rand(($this->image->height()-10)/2, $this->image->height()/2);
        }

        return parent::fontSize();
    }
}
