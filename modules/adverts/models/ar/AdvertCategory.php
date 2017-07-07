<?php

namespace app\modules\adverts\models\ar;

use app\modules\adverts\models\aq\AdvertCategoryQuery;
use Yii;

/**
 * This is the model class for table "advert_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 *
 * @property AdvertCategory $parent
 */
class AdvertCategory extends \app\modules\core\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advert_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => AdvertCategory::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'parent_id' => Yii::t('app', 'Parent ID'),
        ];
    }

    /**
     * @inheritdoc
     * @return AdvertCategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdvertCategoryQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(AdvertCategory::className(), ['id' => 'parent_id']);
    }
}
