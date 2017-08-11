<?php

namespace app\modules\authclient\clients;

use app\modules\authclient\models\OAuthToken;
use jumper423\VK;
use Yii;

class VKontakte extends VK implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function createToken(array $tokenConfig = [])
    {
        $tokenConfig['tokenParamKey'] = 'access_token';

        if (!array_key_exists('class', $tokenConfig)) {
            $tokenConfig['class'] = OAuthToken::className();
        }

        return Yii::createObject($tokenConfig);
    }


    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        return $this->getUserAttributes()['photo'];
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->getUserAttributes()['email'];
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->getUserAttributes()['first_name'];
    }
    /**
     * @return string
     */
    public function getGender()
    {
        return $this->getUserAttributes()['gender'];
    }


    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->getUserAttributes()['last_name'];
    }

    /**
     * @return string
     */
    public function getProfileUrl()
    {
        return $this->getUserAttributes()['screen_name'];
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->getUserAttributes()['user_id'];
    }
}