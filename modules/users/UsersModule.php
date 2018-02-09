<?php

namespace app\modules\users;

use Yii;

/**
 * Class UsersModule
 * @package app\modules\users
 */
class UsersModule extends \app\modules\core\base\Module
{
    /**
     * @inheritdoc
     */
    public static function t($message, $category = null, $params = [], $language = null)
    {
        if (!isset(Yii::$app->i18n->translations['app/modules/users/*'])) {
            Yii::$app->i18n->translations['app/modules/users/*'] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'basePath'       => '@app/modules/users/messages',
                'fileMap'        => [
                    'app/modules/users/main' => 'main.php',
                    'app/modules/users/front' => 'front.php',
                    'app/modules/users/back' => 'back.php',
                ],
            ];
        }

        return Yii::t('app/modules/users/'.($category ? : 'main'), $message, $params, $language);
    }
}
