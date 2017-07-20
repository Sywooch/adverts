<?php

namespace app\modules\authclient\clients;

class Facebook extends \yii\authclient\clients\Facebook implements ClientInterface
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
        return $this->getUserAttributes()['name'];
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getProfileUrl()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->getUserAttributes()['id'];
    }
}