<?php

namespace app\modules\users\models\ar;

use Yii;

/**
 * This is the model class for table "user_email_confirm".
 *
 * @property string $action
 * @property string $email
 * @property string $token
 * @property integer $user_id
 *
 * @property User $user
 */
class EmailConfirmToken extends \app\modules\core\db\ActiveRecord
{
    const ACTION_REGISTRATION = 'registration';
    const ACTION_CHANGE_EMAIL = 'changeEmail';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_email_confirm_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token', 'email'], 'required'],
            [['token', 'email'], 'string', 'max' => 128],
            [['subject'], 'in', 'range' => array_keys(self::getAttributeLabels('subject'))],
            [['subject'], 'default', 'value' => self::ACTION_REGISTRATION],
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
            'email' => Yii::t('app', 'Email'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function attributeLabelsConfig()
    {
        return [
            'subject' => [
                self::ACTION_CHANGE_EMAIL => 'Смена почтового ящика',
                self::ACTION_REGISTRATION => 'Регистрация',
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}