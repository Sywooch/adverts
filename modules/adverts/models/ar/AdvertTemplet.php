<?php

namespace app\modules\adverts\models\ar;

use app\modules\users\models\ar\User;
use Yii;
use yii\helpers\ArrayHelper;
use app\modules\core\models\ar\File;

/**
 * This is the model class for table "advert_templet".
 *
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $geography_id
 * @property string $content
 * @property string $expiry_at
 * @property string $updated_at
 *
 * @property AdvertCategory $category
 * @property Geography $geography
 * @property User $user
 */
class AdvertTemplet extends Advert
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advert_templet';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        $attributes = parent::attributes();
        unset($attributes['created_at']);
        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'content', 'category_id', 'currency_id', 'geography_id', 'content', 'status', 'expiry_at', 'min_price', 'max_price'
            ], 'safe']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(AdvertCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'currency_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeography()
    {
        return $this->hasOne(Geography::className(), ['id' => 'geography_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Try to find a templet by user id or creates new one.
     * @param integer $userId
     * @return \app\modules\adverts\models\ar\AdvertTemplet
     */
    public static function getByUserId($userId)
    {
        if (!$model = self::find()->where(['user_id' => $userId])->one()) {
            $model = new self(['user_id' => $userId]);
            $model->save();
        }
        return  $model;
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->validate($attributeNames)) {

        }

        return parent::save($runValidation = true, $attributeNames = null);
    }

    /**
     * Clears all attributes.
     * @return bool
     */
    public function clear()
    {
        foreach ($this->attributes() as $attribute) {
            if (in_array($attribute, ['id', 'user_id'])) {
                continue;
            }
            $this->$attribute = null;
        }
        return $this->save();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['owner_id' => 'id'])->onCondition([
            'owner_model_name' => static::shortClassName()
        ]);
    }
}