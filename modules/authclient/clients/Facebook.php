<?php

namespace app\modules\authclient\clients;

class Facebook extends \yii\authclient\clients\Facebook implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public $attributeNames = [
        'name', 'first_name', 'middle_name', 'last_name', 'email', 'picture', 'gender', 'link', 'location', 'locale', 'timezone',
    ];

    /**
     * @return string
     */
    public function getAvatarUrl()
    {
        $picture = $this->getUserAttributes()['picture'];
        if (isset($picture['data']) && isset($picture['data']['url'])) {
            return $picture['data']['url'];
        }
        return '';
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
        return $this->getUserAttributes()['link'];
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->getUserAttributes()['id'];
    }
}