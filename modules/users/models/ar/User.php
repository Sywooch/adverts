<?php

namespace app\modules\users\models\ar;

use app\modules\authclient\clients\ClientInterface;
use app\modules\authclient\clients\ClientTrait;
use app\modules\authclient\models\ar\AuthClientUser;
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
 * @property AuthClientUser $authClientUser
 * @property Profile $profile
 */
class User extends UserIdentity
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_BANNED = -1;
    
    const SCENARIO_NEW_USER = 'newUser';
    const SCENARIO_NEW_AUTH_CLIENT_USER = 'newAuthClientUser';
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
            self::SCENARIO_NEW_AUTH_CLIENT_USER => []
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
            'status'               => UsersModule::t('Ствтус'),
            'gridRoleSearch'       => UsersModule::t('Roles'),
            'created_at'           => UsersModule::t('Created'),
            'updated_at'           => UsersModule::t('Updated'),
            'password'             => UsersModule::t('Пароль'),
            'repeatPassword'       => UsersModule::t('Repeat password'),
            'email_confirmed'      => UsersModule::t('E-mail confirmed'),
            'email'                => UsersModule::t('E-mail'),
            'is_from_service'      => UsersModule::t('Is from social'),
            'passwordNotEncrypted' => UsersModule::t('Password'),
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
    public function load($data, $formName = null)
    {
        $profileLoaded = $this->profile->load($data);
        $selfLoaded = parent::load($data, $formName);
        return $profileLoaded || $selfLoaded;
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
        if ($success && $this->scenario == self::SCENARIO_NEW_AUTH_CLIENT_USER) {
            $authClientUser = new AuthClientUser;
            $authClientUser->setClientAttributes($authClient);
            $authClientUser->user_id = $this->id;
            $success = $authClientUser->save();
            $this->populateRelation('authClientUser', $authClientUser);
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
    public function getAuthClientUser()
    {
        return $this->hasOne(AuthClientUser::className(), ['user_id' => 'id']);
    }
}