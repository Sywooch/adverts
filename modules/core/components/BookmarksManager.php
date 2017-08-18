<?php

namespace app\modules\core\components;

use Yii;
use yii\base\Component;

class BookmarksManager extends Component
{
    /**
     * @var BookmarksStorageInterface
     */
    protected $_storage;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->_storage = Yii::$app->user->isGuest ? new SessionBookmarksStorage() : new DbBookmarksStorage();
    }

    /**
     * @param string $ownerModelName
     * @param integer $ownerId
     * @return bool
     */
    public function add($ownerModelName, $ownerId)
    {
        return $this->_storage->add($ownerModelName, $ownerId, Yii::$app->user->id);
    }

    /**
     * @param string $ownerModelName
     * @param integer $ownerId
     * @return bool
     */
    public function delete($ownerModelName, $ownerId)
    {
        return $this->_storage->delete($ownerModelName, $ownerId, Yii::$app->user->id);
    }

    /**
     * @param string $ownerModelName
     * @param integer $ownerId
     * @return bool
     */
    public function toggle($ownerModelName, $ownerId)
    {
        return $this->_storage->toggle($ownerModelName, $ownerId, Yii::$app->user->id);
    }

    /**
     * @param string $ownerModelName
     * @param integer $ownerId
     * @return bool
     */
    public function has($ownerModelName, $ownerId)
    {
        return $this->_storage->has($ownerModelName, $ownerId, Yii::$app->user->id);
    }

    /**
     * Copies all bookmarks from session to DB storage.
     */
    public function copyFromSessionToDb()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        if (!$this->_storage instanceof DbBookmarksStorage) {
            $this->_storage = new DbBookmarksStorage();
        }
        $sessionStorage = new SessionBookmarksStorage();
        $this->_storage->batchInsert($sessionStorage->getList(), Yii::$app->user->id);
    }
}