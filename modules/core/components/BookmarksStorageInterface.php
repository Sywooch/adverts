<?php

namespace app\modules\core\components;

interface BookmarksStorageInterface
{
    /**
     * @param string $ownerModelName
     * @param integer $ownerId
     * @param null|integer $userId
     * @return bool
     */
    public function add($ownerModelName, $ownerId, $userId = null);

    /**
     * @param string $ownerModelName
     * @param integer $ownerId
     * @param null|integer $userId
     * @return bool
     */
    public function delete($ownerModelName, $ownerId, $userId = null);

    /**
     * @param string $ownerModelName
     * @param integer $ownerId
     * @param null|integer $userId
     * @return bool
     */
    public function toggle($ownerModelName, $ownerId, $userId = null);

    /**
     * @param string $ownerModelName
     * @param integer $ownerId
     * @param null|integer $userId
     * @return bool
     */
    public function has($ownerModelName, $ownerId, $userId = null);

    /**
     * @param null|integer $ownerModelName
     * @param null|integer $userId
     * @return mixed
     */
    public function getData($ownerModelName = null, $userId = null);

    /**
     * @param array $data
     * @return mixed
     */
    public function batchInsert($data);
}