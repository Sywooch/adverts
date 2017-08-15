<?php

namespace app\modules\core\base;

use Yii;
use yii\db\Exception;

/**
 * Class Module
 * @package app\modules\core\base
 */
class Module extends \yii\base\Module
{
    /**
     * @var string ajax layout file (does not required). If does not set then response would render without layout any file.
     */
    public $layoutAjax;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->getBehavior('endSideBehavior')) {
            $this->setEndSideFolders();
        }
    }

    /**
     * Translates a message to the specified language.
     *
     * This is a shortcut method of [[\yii\BaseYii::t()]].
     *
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
     * [[\yii\base\Application::language|application language]] will be used.
     * @return string the translated message.
     * @throws Exception
     */
    public static function t($message, $category = null, $params = [], $language = null)
    {
        throw new Exception('Нужно реалиховать ' . self::className() . '::t()');
    }
}