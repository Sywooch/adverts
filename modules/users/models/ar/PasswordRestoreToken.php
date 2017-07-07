<?php

namespace app\modules\users\models\ar;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "user_password_restore_token".
 *
 * @property integer $user_id
 * @property string $token
 * @property string $expiry_at
 *
 * @property User $user
 */
class PasswordRestoreToken extends \app\modules\core\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_password_restore_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token'], 'required'],
            [['user_id'], 'integer'],
            [['expiry_at'], 'default', 'value' => new Expression('NOW() + INTERVAL 1 DAY')],
            [['token'], 'string', 'max' => 128],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'token' => Yii::t('app', 'Token'),
            'expiry_at' => Yii::t('app', 'Expire At'),
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
     * @return \app\modules\users\models\aq\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\users\models\aq\UserQuery(get_called_class());
    }
}
