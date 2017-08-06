<?php

namespace app\modules\authclient\models;

class OAuthToken extends \yii\authclient\OAuthToken
{
    /**
     * @inheritdoc
     */
    public $tokenParamKey = 'access_token';
}