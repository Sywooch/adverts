<?php

namespace app\modules\core\models\ar;

use app\modules\users\models\ar\User;
use Yii;

/**
 * This is the model class for table "look".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $owner_id
 * @property string $owner_model_name
 * @property integer $value
 *
 * @property User $user
 */
class Look extends \app\modules\core\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'look';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'owner_model_name'], 'required'],
            [['user_id', 'owner_id', 'value'], 'integer'],
            [['owner_model_name'], 'string'],
            [['user_id'], 'exist', 'skipOnEmpty' => true, 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'owner_model_name' => Yii::t('app', 'Owner Model Name'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \app\modules\core\models\aq\LookQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\core\models\aq\LookQuery(get_called_class());
    }

    /**
     * Adds one more unit to the value.
     * @return bool
     */
    public function plus()
    {
        $this->value++;
        return $this->save();
    }
}
