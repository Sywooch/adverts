<?php

namespace app\modules\core\components;

use Yii;
use yii\base\Component;
use yii\web\Cookie;

class SessionBookmarksStorage extends Component implements BookmarksStorageInterface
{
    const STORAGE_COOKIE_PARAM_NAME = '_bookmarks';

    /**
     * @inheritdoc
     */
    public function add($ownerModelName, $ownerId, $userId = null)
    {
        $data = $this->_getData();
        if (!isset($data[$ownerModelName])) {
            $data[$ownerModelName] = [];
        }
        if (!isset($data[$ownerModelName][$ownerId])) {
            $data[$ownerModelName][$ownerId] = true;
        }
        $this->_setData($data);
    }

    /**
     * @inheritdoc
     */
    public function delete($ownerModelName, $ownerId, $userId = null)
    {
        $data = $this->_getData();
        if (isset($data[$ownerModelName]) && isset($data[$ownerModelName][$ownerId])) {
            unset($data[$ownerModelName][$ownerId]);
        }
        $this->_setData($data);
    }

    /**
     * @inheritdoc
     */
    public function toggle($ownerModelName, $ownerId, $userId = null)
    {
        return self::has($ownerModelName, $ownerId)
            ? self::delete($ownerModelName, $ownerId)
            : self::add($ownerModelName, $ownerId);
    }

    /**
     * @inheritdoc
     */
    public function has($ownerModelName, $ownerId, $userId = null)
    {
        $data = $this->_getData();
        return isset($data[$ownerModelName]) && isset($data[$ownerModelName][$ownerId]);
    }

    /**
     * @inheritdoc
     */
    public function getData($ownerModelName = null, $userId = null)
    {
        $data = $this->_getData($ownerModelName);
        if ($ownerModelName && isset($data[$ownerModelName])) {
            return $data[$ownerModelName];
        } else {
            return [];
        }
    }

    /**
     * @inheritdoc
     */
    public function batchInsert($data = [], $userId = null)
    {
        $records = [];
        foreach ($data as $ownerModelName => $ids) {
            foreach ($ids as $id) {
                $records[] = [
                    'owner_model_name' => $ownerModelName,
                    'owner_id' => $id,
                    'user_id' => $userId
                ];
            }
        }
        if (count($records)) {
            Yii::$app->db->createCommand()->batchInsert(Bookmark::tableName(), [
                'owner_model_name', 'owner_id', 'user_id'
            ], $records)->execute();
        }
    }

    /**
     * @return array mixed
     */
    protected function _getData()
    {
        return Yii::$app->request->cookies->getValue(self::STORAGE_COOKIE_PARAM_NAME, []);
    }

    /**
     * @param array $data
     */
    protected function _setData($data)
    {
        return Yii::$app->response->cookies->add(new Cookie([
            'name' => self::STORAGE_COOKIE_PARAM_NAME,
            'value' => $data
        ]));
    }
}