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
    public $loginUrl = ['/user/auth/login'];

    /**
     * @return bool
     */
    public function getIsSuperadmin()
    {
        return @Yii::$app->user->identity->superadmin == 1;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return @Yii::$app->user->identity->username;
    }

    /**
     * @inheritdoc
     */
    protected function afterLogin($identity, $cookieBased, $duration)
    {
        AuthHelper::updatePermissions($identity);
        parent::afterLogin($identity, $cookieBased, $duration);
    }

    /**
     * Возвращает шаблон.
     * @return \app\modules\adverts\models\Advert
     */
    public function getTemplet()
    {
        $query = Advert::find()->where(['user_id' => $this->id, 'is_templet' => 1]);
        return $query->one() ? :  Advert::createTemplet($this->id);
    }
}