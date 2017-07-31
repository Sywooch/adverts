<?php

namespace app\modules\core\db;

use app\modules\core\models\ar\Comment;
use app\modules\core\models\ar\Like;
use app\modules\core\models\ar\Look;
use yii\db\Expression;

class ActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * @var bool
     */
    protected $_countDislikes = false;

    /**
     * @var bool
     */
    protected $_countLikes = false;

    /**
     * @var bool
     */
    protected $_countLooks = false;

    /**
     *
     */
    public function countDislikes()
    {
        $this->_countDislikes = true;
        return $this;
    }

    /**
     *
     */
    public function countLikes()
    {
        $this->_countLikes = true;
        return $this;
    }

    /**
     *
     */
    public function countLooks()
    {
        $this->_countLooks = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function addMainSelect()
    {
        $modelName = $this->modelClass;
        return $this->addSelect(new Expression($modelName::tableName() . '.*'));
    }

    /**
     * @inheritdoc
     */
    protected function createModels($rows)
    {
        $models = [];
        $modelsIds = [];
        if ($this->asArray) {
            if ($this->indexBy === null) {
                return $rows;
            }
            foreach ($rows as $row) {
                if (is_string($this->indexBy)) {
                    $key = $row[$this->indexBy];
                } else {
                    $key = call_user_func($this->indexBy, $row);
                }
                $models[$key] = $row;
            }
        } else {
            /* @var $class ActiveRecord */
            $class = $this->modelClass;
            if ($this->indexBy === null) {
                foreach ($rows as $row) {
                    $model = $class::instantiate($row);
                    $modelClass = get_class($model);
                    $modelClass::populateRecord($model, $row);
                    $models[] = $model;
                    $modelsIds[] = $model->id;
                }
            } else {
                foreach ($rows as $row) {
                    $model = $class::instantiate($row);
                    $modelClass = get_class($model);
                    $modelClass::populateRecord($model, $row);
                    if (is_string($this->indexBy)) {
                        $key = $model->{$this->indexBy};
                    } else {
                        $key = call_user_func($this->indexBy, $model);
                    }
                    $models[$key] = $model;
                    $modelsIds[] = $model->id;
                }
            }
        }

        $likes = [];
        if ($this->_countLikes) {
            $likes = Like::find()->select([
                'owner_id', 'likesCount' => 'COUNT(*)'
            ])->where([
                'owner_id' => $modelsIds,
                'owner_model_name' => $class::shortClassName(),
                'value' => Like::LIKE_VALUE
            ])->groupBy('owner_id')->indexBy('owner_id')->asArray()->all();
        }

        $dislikes = [];
        if ($this->_countDislikes) {
            $dislikes = Like::find()->select([
                'owner_id', 'dislikesCount' => 'COUNT(*)'
            ])->where([
                'owner_id' => $modelsIds,
                'owner_model_name' => $class::shortClassName(),
                'value' => Like::DISLIKE_VALUE
            ])->groupBy('owner_id')->indexBy('owner_id')->asArray()->all();
        }

        $looks = [];
        if ($this->_countLooks) {
            $looks = Look::find()->select([
                'owner_id', 'looksCount' => 'SUM(' . Look::tableName() . '.value)'
            ])->where([
                'owner_id' => $modelsIds,
                'owner_model_name' => $class::shortClassName(),
            ])->groupBy('owner_id')->indexBy('owner_id')->asArray()->all();
        }

        foreach ($models as $model) {
            if (isset($likes[$model->id])) {
                $model->likesCount = $likes[$model->id]['likesCount'];
            }
            if (isset($dislikes[$model->id])) {
                $model->dislikesCount = $dislikes[$model->id]['dislikesCount'];
            }
            if (isset($looks[$model->id])) {
                $model->looksCount = $looks[$model->id]['looksCount'];
            }
        }

        return $models;
    }

    /**
     * Adds conditions for the counting of likes and dislikes.
     * @return $this
     */
    /*public function addLikesCountSelectConditions()
    {
        $modelName = $this->modelClass;
        $ownerModelName = $modelName::shortClassName();
        $selfTable = self::getPrimaryTableName();
        return $this
            ->addSelect([
                'likesCount' => 'COUNT(`likesCount`.`id`)',
                'dislikesCount' => 'COUNT(`dislikesCount`.`id`)',
            ])->leftJoin(['likesCount' => Like::tableName()], [
                'likesCount.owner_id' => new Expression("{$selfTable}.id"),
                'likesCount.owner_model_name' => $ownerModelName,
                'likesCount.value' => 1
            ])->leftJoin(['dislikesCount' => Like::tableName()], [
                'dislikesCount.owner_id' => new Expression("{$selfTable}.id"),
                'dislikesCount.owner_model_name' => $ownerModelName,
                'dislikesCount.value' => 0
            ]);
    }*/

    /**
     * Adds conditions for the counting of looks.
     * @return $this
     */
    /*public function addLooksCountSelectConditions()
    {
        $modelName = $this->modelClass;
        $ownerModelName = $modelName::shortClassName();
        $selfTable = self::getPrimaryTableName();
        return $this
            ->addSelect([
                'looksCount' => 'COUNT(`look`.`value`)',
            ])->leftJoin(Look::tableName(), [
                'look.owner_id' => new Expression("{$selfTable}.id"),
                'look.owner_model_name' => $ownerModelName,
            ]);
    }*/

    /**
     * Adds conditions for the counting of looks.
     * @return $this
     */
    /*public function addCommentsCountSelectConditions()
    {
        $modelName = $this->modelClass;
        $ownerModelName = $modelName::shortClassName();
        $selfTable = self::getPrimaryTableName();
        return $this
            ->addSelect([
                'commentsCount' => 'COUNT(`comment`.`id`)',
            ])->leftJoin(Comment::tableName(), [
                'comment.owner_id' => new Expression("{$selfTable}.id"),
                'comment.owner_model_name' => $ownerModelName,
            ]);
    }*/
}