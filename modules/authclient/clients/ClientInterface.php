<?php

namespace app\modules\authclient\clients;

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