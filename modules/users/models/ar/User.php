<?php

namespace app\modules\users\models\ar;

use app\modules\authclient\clients\ClientInterface;
use app\modules\authclient\clients\ClientTrait;
use app\modules\authclient\models\ar\UserAuthClient;
use app\modules\core\behaviors\TimestampBehavior;
use app\modules\users\components\UserIdentity;
use app\modules\users\models\aq\UserQuery;
use app\modules\users\UsersModule;
use yii\authclient\BaseClient;
use Yii;
use yii\authclient\OAuth2;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
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
 *
 * @property string $fullName
 * @property bool $isAuthClient
 * @property string $url
 *
 * @property UserAuthClient $userAuthClient
 * @property Profile $profile
 */
class User extends UserIdentity
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_BANNED = -1;
    
    const SCENARIO_NEW_USER = 'newUser';
    const SCENARIO_NEW_USER_AUTH_CLIENT = 'newUserAuthClient';
    const SCENARIO_CHANGE_PASSWORD = 'changePassword';

    /**
     * @var string нехешированный пароль
     */
    public $passwordNotEncrypted;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_NEW_USER_AUTH_CLIENT => []
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'in', 'range' => array_keys(self::getAttributeLabels('status'))],
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'app\modules\users\models\ar\User', 'targetAttribute' => 'email'],
            ['passwordNotEncrypted', 'required', 'on' => [self::SCENARIO_NEW_USER, self::SCENARIO_CHANGE_PASSWORD]],
            ['passwordNotEncrypted', 'trim'],
            ['passwordNotEncrypted', 'string', 'min' => 4, 'max' => 32, 'on' => [self::SCENARIO_NEW_USER, self::SCENARIO_CHANGE_PASSWORD]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                   => 'ID',
            'superadmin'           => UsersModule::t('Superadmin'),
            'confirmation_token'   => UsersModule::t('Confirmation token'),
            'status'               => UsersModule::t('Статус'),
            'created_at'           => UsersModule::t('Зарегистрирован'),
            'updated_at'           => UsersModule::t('Последнее обновление'),
            'password'             => UsersModule::t('Пароль'),
            'repeatPassword'       => UsersModule::t('Repeat password'),
            'email_confirmed'      => UsersModule::t('E-mail confirmed'),
            'email'                => UsersModule::t('Почтовый ящик'),
            'is_from_service'      => UsersModule::t('Из соцсети'),
            'passwordNotEncrypted' => UsersModule::t('Пароль'),
            'fullName'             => UsersModule::t('ФИО'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function attributeLabelsConfig()
    {
        return [
            'status' => [
                self::STATUS_ACTIVE   => UsersModule::t('Активный'),
                self::STATUS_INACTIVE => UsersModule::t('Неактивный'),
                self::STATUS_BANNED => UsersModule::t('Заблокирован'),
            ]
        ];
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateAuthKey();
        }

        if ($this->passwordNotEncrypted) {
            $this->setPassword($this->passwordNotEncrypted);
        }

        return parent::beforeSave($insert);
    }

    /**
     * User registration.
     * @param OAuth2|ClientInterface $authClient
     * @return bool
     */
    public function register($authClient = null)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $success = $this->save();
        if ($success) {
            $attributes = [
                'user_id' => $this->id,
            ];
            if ($authClient) {
                $attributes['first_name'] = $authClient->firstName;
                $attributes['last_name'] = $authClient->lastName;
            }
            $profile = new Profile($attributes);
            $success = $profile->save();
        }
        if ($success && $this->scenario == self::SCENARIO_NEW_USER_AUTH_CLIENT) {
            $userAuthClient = new UserAuthClient;
            $userAuthClient->setClientAttributes($authClient);
            $userAuthClient->user_id = $this->id;
            $success = $userAuthClient->save();
            $this->populateRelation('userAuthClient', $userAuthClient);
        }

        $success ? $transaction->commit() : $transaction->rollBack();
        return $success;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAuthClient()
    {
        return $this->hasOne(UserAuthClient::className(), ['user_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->profile->fullName;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->profile->url;
    }

    /**
     * @return bool
     */
    public function getIsAuthClient()
    {
        return !$this->email && $this->userAuthClient;
    }
}