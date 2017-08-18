<?php

namespace app\modules\core\db;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertTemplet;
use app\modules\core\models\ar\Comment;
use app\modules\core\models\ar\File;
use app\modules\core\models\ar\Like;
use app\modules\users\models\ar\User;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property integer $commentsCount
 * @property integer $dislikesCount
 * @property integer $isDislikedCurrentUser
 * @property bool $isBookmarkedCurrentUser
 * @property bool $isBookmarkedCurrentUserInDb
 * @property integer $likesCount
 * @property integer $isLikedCurrentUser
 * @property integer $looksCount
 *
 * @property Like $dislikeCurrentUser
 * @property Like[] $dislikes
 * @property Like $likeCurrentUser
 * @property Like[] $likes
 * @property File[] $files
 * @property User $user
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    const LIKE_VALUE = 1;
    const DISLIKE_VALUE = 0;

    protected static $_maxFilesCount = 3;

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }

    /**
     * @return array
     */
    public static function virtualAttributes()
    {
        return [
            'likesCount', 'dislikesCount', 'looksCount', 'commentsCount', 'isBookmarkedCurrentUserInDb',
            'isLikedCurrentUser', 'isDislikedCurrentUser'
        ];
    }

    /**
     * @inheritdoc
     */
    public function safeAttributes()
    {
        return ArrayHelper::merge(parent::safeAttributes(), static::virtualAttributes());
    }

    /**
     * @inheritdoc
     */
    public function getDirtyAttributes($names = null)
    {
        $attributes = parent::getDirtyAttributes($names);
        foreach (static::virtualAttributes() as $attribute) {
            unset($attributes[$attribute]);
        }
        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), static::virtualAttributes());
    }

    /**
     * @param string $attribute
     * @param string|null $value
     * @return mixed
     */
    public static function getAttributeLabels($attribute, $value = null)
    {
        $config = static::attributeLabelsConfig();
        if (isset($config[$attribute])) {
            if ($value === null) {
                return $config[$attribute];
            } else if (isset($config[$attribute][$value])) {
                return $config[$attribute][$value];
            }
        }
        return $value;
    }

    /**
     * @return array
     */
    public static function attributeLabelsConfig()
    {
        return [];
    }

    /**
     * Returns the short class name.
     */
    public static function shortClassName()
    {
        $class = get_called_class();
        return substr($class, strrpos($class, '\\') + 1);
    }

    /**
     * Returns full class name by its shortcut.
     * @param string $shortClassName
     * @return ActiveRecord|null
     * @throws Exception
     */
    public static function getFullClassName($shortClassName)
    {
        switch ($shortClassName) {
            case Advert::shortClassName():
                return Advert::className();
            case AdvertTemplet::shortClassName():
                return AdvertTemplet::className();
        }

        throw new Exception("Класса {$shortClassName} не существует");
    }

    /**
     * Returns maximum count of permitted related files.
     * @return integer
     */
    public static function getMaxFilesCount()
    {
        return self::$_maxFilesCount;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        $tableName = Comment::tableName();
        return $this->hasMany(Comment::className(), ['owner_id' => 'id'])->onCondition([
            "{$tableName}.owner_model_name" => self::shortClassName(),
        ])->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        $tableName = File::tableName();
        return $this->hasMany(File::className(), ['owner_id' => 'id'])->onCondition([
            "{$tableName}.owner_model_name" => static::shortClassName()
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return bool
     */
    public function  getIsBookmarkedCurrentUser()
    {
        return !Yii::$app->user->isGuest
            ? $this->isBookmarkedCurrentUserInDb
            : Yii::$app->bookmarksManager->has($this::shortClassName(), $this->id);
    }
}