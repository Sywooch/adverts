<?php

namespace app\modules\users\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use app\modules\users\UsersModule;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property integer $email_confirmed
 * @property string $auth_key
 * @property string $password_hash
 * @property string $confirmation_token
 * @property string $bind_to_ip
 * @property string $registration_ip
 * @property integer $status
 * @property integer $superadmin
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_BANNED = -1;
    
    const SCENARIO_NEW_USER = 'newUser';
    const SCENARIO_NEW_SERVICE_USER = 'newServiceUser';
    const SCENARIO_CHANGE_PASSWORD = 'changePassword';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * getStatusList
     * @return array
     */
    public static function getStatusList()
    {
        return array(
            self::STATUS_ACTIVE   => UsersModule::t('back', 'Active'),
            self::STATUS_INACTIVE => UsersModule::t('back', 'Inactive'),
            self::STATUS_BANNED   => UsersModule::t('back', 'Banned'),
        );
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required', 'on' => [self::SCENARIO_NEW_USER, self::SCENARIO_CHANGE_PASSWORD]],
            ['username', 'unique'],
            ['username', 'trim'],
            
            ['is_from_service', 'required', 'on' => [self::SCENARIO_NEW_SERVICE_USER]],

            [['status', 'email_confirmed'], 'integer'],

            ['email', 'email'],
            ['email', 'validateEmailConfirmedUnique'],

            ['password', 'required', 'on' => [self::SCENARIO_NEW_USER, self::SCENARIO_CHANGE_PASSWORD]],
            ['password', 'string', 'max' => 255, 'on' => [self::SCENARIO_NEW_USER, self::SCENARIO_CHANGE_PASSWORD]],
            ['password', 'trim', 'on' => [self::SCENARIO_NEW_USER, self::SCENARIO_CHANGE_PASSWORD]],

            ['repeat_password', 'required', 'on' => [self::SCENARIO_NEW_USER, self::SCENARIO_CHANGE_PASSWORD]],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
            
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                 => 'ID',
            'username'           => UsersModule::t('back', 'Login'),
            'superadmin'         => UsersModule::t('back', 'Superadmin'),
            'confirmation_token' => 'Confirmation Token',
            'registration_ip'    => UsersModule::t('back', 'Registration IP'),
            'bind_to_ip'         => UsersModule::t('back', 'Bind to IP'),
            'status'             => UsersModule::t('back', 'Status'),
            'gridRoleSearch'     => UsersModule::t('back', 'Roles'),
            'created_at'         => UsersModule::t('back', 'Created'),
            'updated_at'         => UsersModule::t('back', 'Updated'),
            'password'           => UsersModule::t('back', 'Password'),
            'repeat_password'    => UsersModule::t('back', 'Repeat password'),
            'email_confirmed'    => UsersModule::t('back', 'E-mail confirmed'),
            'email'              => 'E-mail',
            'is_from_service'    => UsersModule::t('back', 'Из сoцсети'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {

    }

    /**
     * @param $val
     * @return mixed
     */
    public static function getStatusValue($val)
    {
        $ar = self::getStatusList();

        return isset($ar[$val]) ? $ar[$val] : $val;
    }
}