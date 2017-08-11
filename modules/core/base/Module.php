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
    const BACKEND_PATH = 'back';
    const FRONTEND_PATH = 'front';

    const ENDSIDE_ADMIN_PARAM_NAME = '_like_admin';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $path = Yii::$app->session->get(self::ENDSIDE_ADMIN_PARAM_NAME, false) ? '\admin' : '\front';
        $this->controllerNamespace .= $path;
        $this->viewPath .= $path;
        Yii::setAlias('@mail', Yii::getAlias("@app/mail{$path}"));
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
     */
    public static function t($message, $category = null, $params = [], $language = null)
    {
        throw new Exception('Нужно реалиховать ' . self::className() . '::t()');
    }
}