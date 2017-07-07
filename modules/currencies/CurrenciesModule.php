<?php

namespace app\modules\currencies;

use Yii;

/**
 * Class CurrenciesModule
 * @package app\modules\users
 */
class CurrenciesModule extends \app\modules\core\base\Module
{
    /**
     * @inheritdoc
     */
    public static function t($message, $category = null, $params = [], $language = null)
    {
        if (!isset(Yii::$app->i18n->translations['app/modules/currencies/*'])) {
            Yii::$app->i18n->translations['app/modules/currencies/*'] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'basePath'       => '@app/modules/currencies/messages',
                'fileMap'        => [
                    'app/modules/currencies/main' => 'main.php',
                ],
            ];
        }

        return Yii::t('app/modules/currencies/'.($category ? : 'main'), $message, $params, $language);
    }
}
