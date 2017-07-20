<?php

namespace app\modules\authclient\clients;

class VKontakte extends \yii\authclient\clients\VKontakte implements ClientInterface
{
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