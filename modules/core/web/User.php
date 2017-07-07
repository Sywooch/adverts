<?php

namespace app\modules\core\web;

use app\modules\adverts\models\Advert;

/**
 * Class User
 * @package app\components
 */
class User extends \yii\web\User
{
    /**
     * @inheritdoc
     */
    public $enableAutoLogin = true;

    /**
     * @inheritdoc
     */
    public $loginUrl = ['/user/auth/login'];

    /**
     * @inheritdoc
     */
    public $identityClass = 'app\modules\users\models\ar\User';

    /**
     * Является ли пользователь суперадминистратором.
     * @return bool
     */
    public function getIsSuperadmin()
    {
        return @Yii::$app->user->identity->superadmin == 1;
    }

    /**
     * Логин пользователя.
     * @return string
     */
    public function getUsername()
    {
        return @Yii::$app->user->identity->username;
    }
}