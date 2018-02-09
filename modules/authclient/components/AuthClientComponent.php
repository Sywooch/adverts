<?php

namespace app\modules\authclient\components;

use app\modules\authclient\clients\ClientInterface;
use app\modules\authclient\models\ar\UserAuthClient;
use yii\authclient\BaseClient;

class AuthClientComponent extends \yii\base\Component
{
    const CLIENT_FACEBOOK = 'facebook';
    const CLIENT_GITHUB = 'github';
    const CLIENT_GOOGLE = 'google';
    const CLIENT_LINKEDIN = 'linkedin';
    const CLIENT_LIVE = 'live';
    const CLIENT_TWITTER = 'twitter';
    const CLIENT_VKONTAKTE = 'vkontakte';
    const CLIENT_YANDEX = 'yandex';

    /**
     * @return array
     */
    public function getClientsNames()
    {
        return [
            self::CLIENT_FACEBOOK,
            self::CLIENT_GITHUB,
            self::CLIENT_GOOGLE,
            self::CLIENT_LINKEDIN,
            self::CLIENT_LIVE,
            self::CLIENT_TWITTER,
            self::CLIENT_VKONTAKTE,
            self::CLIENT_YANDEX
        ];
    }
}
