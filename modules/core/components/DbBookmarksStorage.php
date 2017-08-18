<?php

namespace app\modules\core\components;

use app\modules\core\models\ar\Bookmark;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class DbBookmarksStorage extends Component implements BookmarksStorageInterface
{
    /**
     * @inheritdoc
     */
    public function add($ownerModelName, $ownerId, $userId = null)
    {
        return (new Bookmark([
            'owner_model_name' => $ownerModelName,
            'owner_id' => $ownerId,
            'user_id' => Yii::$app->user->id,
        ]))->save();
    }

    /**
     * @inheritdoc
     */
    public function delete($ownerModelName, $ownerId, $userId = null)
    {
        if ($model = Bookmark::find()->where([
            'owner_model_name' => $ownerModelName,
            'owner_id' => $ownerId,
        ])->one()) {
            return $model->delete();
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    public function toggle($ownerModelName, $ownerId, $userId = null)
    {
        if (!$userId) {
            throw new Exception('Не указан параметр userId');
        }
        return self::has($ownerModelName, $ownerId, $userId)
            ? self::delete($ownerModelName, $ownerId, $userId)
            : self::add($ownerModelName, $ownerId, $userId);
    }

    /**
     * @inheritdoc
     */
    public function has($ownerModelName, $ownerId, $userId = null)
    {
        return Bookmark::find()->where([
            'owner_model_name' => $ownerModelName,
            'owner_id' => $ownerId,
        ])->count();
    }

    /**
     * @inheritdoc
     */
    public function getData($ownerModelName = null, $userId = null)
    {

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
}