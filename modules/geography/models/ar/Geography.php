<?php

namespace app\modules\geography\models\ar;

use app\modules\geography\models\aq\GeographyQuery;
use Yii;

/**
 * This is the model class for table "geography".
 *
 * @property integer $id
 * @property integer $type
 * @property string $title
 * @property integer $active
 * @property integer $parent_id
 *
 * @property Advert[] $adverts
 * @property AdvertTemplet[] $advertTemplets
 * @property Geography $parent
 * @property Geography[] $geographies
 */
class Geography extends \app\modules\core\db\ActiveRecord
{
    const TYPE_COUNTRY = 'country';
    const TYPE_REGION = 'region';
    const TYPE_CITY = 'city';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'geography';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id', 'active', 'parent_id'], 'integer'],
            [['title'], 'required'],
            [['title'], 'string', 'max' => 64],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Geography::className(), 'targetAttribute' => ['parent_id' => 'service_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'Тип'),
            'title' => Yii::t('app', 'Название'),
            'active' => Yii::t('app', 'Активно'),
            'parent_id' => Yii::t('app', 'Parent ID'),
        ];
    }
    /**
     * @inheritdoc
     */
    public static function attributeLabelsConfig()
    {
        return [
            'type' => [
                self::TYPE_CITY => self::TYPE_CITY,
                self::TYPE_COUNTRY => self::TYPE_COUNTRY,
                self::TYPE_REGION => self::TYPE_REGION
            ]
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdverts()
    {
        return $this->hasMany(Advert::className(), ['geography_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdvertTemplets()
    {
        return $this->hasMany(AdvertTemplet::className(), ['geography_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Geography::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGeographies()
    {
        return $this->hasMany(Geography::className(), ['parent_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return GeographyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GeographyQuery(get_called_class());
    }
}
