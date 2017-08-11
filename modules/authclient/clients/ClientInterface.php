<?php

namespace app\modules\authclient\clients;

/**
 * @property string $avatarUrl
 * @property string $email
 * @property string $firstName
 * @property string $lastName
 * @property string $profileUrl
 * @property integer $userId
 */
interface ClientInterface
{
    /**
     * @return string
     */
    public function getAvatarUrl();

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @return string
     */
    public function getGender();

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @return string
     */
    public function getProfileUrl();

    /**
     * @return string
     */
    public function getUserId();
}