<?php

namespace app\modules\core\db;

use app\modules\core\models\ar\Bookmark;
use app\modules\core\models\ar\Comment;
use app\modules\core\models\ar\Like;
use app\modules\core\models\ar\Look;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class ActiveQuery extends \yii\db\ActiveQuery
{
    /**
     * @var string
     */
    public $tableName;

    /**
     * @var bool
     */
    protected $_commentsCount = false;

    /**
     * @var bool
     */
    protected $_dislikesCount = false;

    /**
     * @var bool
     */
    protected $_likesCount = false;

    /**
     * @var bool
     */
    protected $_likesCurrentUser = false;

    /**
     * @var bool
     */
    protected $_looksCount = false;

    /**
     * @var bool
     */
    protected $_bookmarksCurrentUser = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->tableName = ($this->modelClass)::tableName();
    }

    /**
     * Whether to load comments count value.
     * @return $this
     */
    public function withCommentsCount()
    {
        $this->_commentsCount = true;
        return $this;
    }

    /**
     * Whether to load dislikes count value.
     * @return $this
     */
    public function withDislikesCount()
    {
        $this->_dislikesCount = true;
        return $this;
    }

    /**
     * Whether to load likes count value.
     * @return $this
     */
    public function withLikesCount()
    {
        $this->_likesCount = true;
        return $this;
    }

    /**
     * Whether to load current user likes.
     * @return $this
     */
    public function withLikesCurrentUser()
    {
        $this->_likesCurrentUser = true;
        return $this;
    }

    /**
     * Whether to load views count value.
     * @return $this
     */
    public function withLooksCount()
    {
        $this->_looksCount = true;
        return $this;
    }

    /**
     * Whether to load current user bookmarks.
     * @return $this
     */
    public function withBookmarksCurrentUser()
    {
        $this->_bookmarksCurrentUser = true;
        return $this;
    }

    /**
     * Adds main table attributes to the select query section.
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
        /* @var $models ActiveRecord[] */
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
        if ($this->_likesCount) {
            $likes = Like::find()->select([
                'owner_id', 'likesCount' => 'COUNT(*)'
            ])->where([
                'owner_id' => $modelsIds,
                'owner_model_name' => $class::shortClassName(),
                'value' => Like::LIKE_VALUE
            ])->groupBy('owner_id')->indexBy('owner_id')->asArray()->all();
        }

        $dislikes = [];
        if ($this->_dislikesCount) {
            $dislikes = Like::find()->select([
                'owner_id', 'dislikesCount' => 'COUNT(*)'
            ])->where([
                'owner_id' => $modelsIds,
                'owner_model_name' => $class::shortClassName(),
                'value' => Like::DISLIKE_VALUE
            ])->groupBy('owner_id')->indexBy('owner_id')->asArray()->all();
        }

        $likesCurrentUser = [];
        if ($this->_likesCurrentUser) {
            $likesCurrentUser = Like::find()->select([
                'owner_id', 'value'
            ])->where([
                'user_id' => Yii::$app->user->id,
                'owner_id' => $modelsIds,
                'owner_model_name' => $class::shortClassName(),
            ])->indexBy('owner_id')->asArray()->all();
        }

        $looks = [];
        if ($this->_looksCount) {
            $looks = Look::find()->select([
                'owner_id', 'looksCount' => 'SUM(' . Look::tableName() . '.value)'
            ])->where([
                'owner_id' => $modelsIds,
                'owner_model_name' => $class::shortClassName(),
            ])->groupBy('owner_id')->indexBy('owner_id')->asArray()->all();
        }

        $comments = [];
        if ($this->_commentsCount) {
            $comments = Comment::find()->select([
                'owner_id', 'commentsCount' => 'COUNT(*)'
            ])->where([
                'owner_id' => $modelsIds,
                'owner_model_name' => $class::shortClassName(),
            ])->groupBy('owner_id')->indexBy('owner_id')->asArray()->all();
        }

        $bookmarks = [];
        if ($this->_bookmarksCurrentUser && !Yii::$app->user->isGuest) {
            $bookmarks = Bookmark::find()->select([
                'owner_id'
            ])->where([
                'user_id' => Yii::$app->user->id,
                'owner_id' => $modelsIds,
                'owner_model_name' => $class::shortClassName(),
            ])->groupBy('owner_id')->indexBy('owner_id')->asArray()->all();
        }

        foreach ($models as $model) {
            $modelId = $model instanceof ActiveRecord ? $model->id : $model['id'];
            $attributes = [
                'likesCount' => isset($likes[$modelId]) ? $likes[$modelId]['likesCount'] : 0,
                'dislikesCount' => isset($dislikes[$modelId]) ? $dislikes[$modelId]['dislikesCount'] : 0,
                'isLikedCurrentUser' => isset($likesCurrentUser[$modelId]) ? $likesCurrentUser[$modelId]['value'] == Like::LIKE_VALUE : false,
                'isDislikedCurrentUser' => isset($likesCurrentUser[$modelId]) ? $likesCurrentUser[$modelId]['value'] == Like::DISLIKE_VALUE : false,
                'looksCount' => isset($looks[$modelId]) ? $looks[$modelId]['looksCount'] : 0,
                'commentsCount' => isset($comments[$modelId]) ? $comments[$modelId]['commentsCount'] : 0,
                'isBookmarkedCurrentUserInDb' => isset($bookmarks[$modelId])
            ];
            if ($model instanceof ActiveRecord) {
                $model->setAttributes($attributes);
            } else {
                $model = ArrayHelper::merge($model, $attributes);
            }
        }

        return $models;
    }

    /**
     * Adds only bookmarked models conditions.
     * @return $this
     */
    public function bookmarked()
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $bookmarkTable = Bookmark::tableName();
        return $this->innerJoin($bookmarkTable, [
            "{$bookmarkTable}.owner_model_name" => $modelClass::shortClassName(),
            self::getPrimaryTableName() . '.id' => new Expression("{$bookmarkTable}.owner_id"),
            "{$bookmarkTable}.user_id" => Yii::$app->user->id
        ]);
    }
}