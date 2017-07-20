<?php

namespace app\modules\authclient\models\ar;

use app\modules\users\models\ar\User;
use Yii;

/**
 * This is the model class for table "auth_client_user".
 *
 * @property string $client_user_id
 * @property string $client_name
 * @property integer $user_id
 * @property string $state
 * @property string $access_token
 * @property string $client_status
 * @property string $avatar_url
 * @property string $profile
 *
 * @property User $user
 */
class AuthClientUser extends \app\modules\core\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_client_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'client_user_id', 'client_name'], 'required'],
            [['user_id', 'client_user_id'], 'integer'],
            [['client_name'], 'string'],
            [['state', 'client_status'], 'string', 'max' => 32],
            [['access_token'], 'string', 'max' => 512],
            [['avatar_url'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'client_user_id' => Yii::t('app', 'Client User ID'),
            'client_name' => Yii::t('app', 'Client Name'),
            'user_id' => Yii::t('app', 'User ID'),
            'state' => Yii::t('app', 'State'),
            'access_token' => Yii::t('app', 'Access Token'),
            'client_status' => Yii::t('app', 'Client Status'),
            'avatar_url' => Yii::t('app', 'Avatar Url'),
            'profile' => Yii::t('app', 'Profile'),
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
     * @return \app\modules\authclient\models\aq\AuthClientUserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\modules\authclient\models\aq\AuthClientUserQuery(get_called_class());
    }
}
