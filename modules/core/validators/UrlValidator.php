<?php

namespace app\modules\core\validators;

class UrlValidator extends \yii\validators\UrlValidator
{
    /**
     * @var string clear indication of host
     */
    public $host;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->host) {
            $this->pattern = '/^{schemes}:\/\/(' . $this->host . ')(?::\d{1,5})?(?:$|[?\/#])/i';
        }
    }
}