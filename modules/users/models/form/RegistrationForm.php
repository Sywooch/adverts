<?php

namespace app\modules\users\models\form;

use app\modules\users\models\ar\EmailConfirmToken;
use app\modules\users\models\ar\Profile;
use yii\base\Model;
use Yii;
use yii\helpers\Html;
use app\modules\users\UsersModule;
use app\modules\users\models\ar\User;

class RegistrationForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $repeatPassword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password', 'repeatPassword'], 'required',],
            [['email', 'password', 'repeatPassword'], 'trim'],
            ['email', 'email', 'message' => UsersModule::t('Неверный формат', 'front')],
            [
                'email', 'unique', 'targetClass' => 'app\modules\users\models\ar\User', 'targetAttribute' => 'email',
                'message' => UsersModule::t('Такой почтовый ящик уже используется', 'front')
            ],
            [
                'email', 'string', 'max' => 128,
                'tooLong' => UsersModule::t('Такой почтовый ящик уже используется', 'front')
            ],
            [
                'password', 'string', 'min' => 4, 'max' => 32,
                'tooShort' => UsersModule::t('Минимальная длина пароля - 4 символа', 'front'),
                'tooLong' => UsersModule::t('Максимальная длина пароля - 32 символа', 'front')
            ],
            [
                'repeatPassword', 'compare', 'compareAttribute' => 'password',
                'message' => UsersModule::t('Повторный пароль отличается', 'front')
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'          => UsersModule::t('Почтовый ящик'),
            'password'       => UsersModule::t('Пароль'),
            'repeatPassword' => UsersModule::t('Повторите пароль'),
        ];
    }

    /**
     * User registrations
     * @return \app\modules\users\models\ar\User|boolean
     */
    public function registerUser()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User([
            'email' => $this->email,
            'passwordNotEncrypted' => $this->password,
            'status' => User::STATUS_INACTIVE,
        ]);

        if ($user->register() && $user->generateEmailConfirmToken(EmailConfirmToken::ACTION_REGISTRATION) && $this->sendConfirmationEmail($user)) {
            return $user;
        } else {
            $this->addError('username', UsersModule::t('Произошла ошибка во время регистрации'));
            return false;
        }
    }

    /**
     * Sending account activation message.
     * @param User $user
     * @return bool
     */
    protected function sendConfirmationEmail($user)
    {
        $params = Yii::$app->params;
        return Yii::$app->mailer
            ->compose('@app/mail/front/users/email-confirm', ['user' => $user])
            ->setFrom($params['adminEmail'])
            ->setTo($user->email)
            ->setSubject(UsersModule::t('Подтверждение регистрации на сайте') . ' ' . $params['siteName'])
            ->send();
    }

    /**
     * Cleaning from XSS attack.
     * @param $attribute
     */
    public function purgeXSS($attribute)
    {
        $this->$attribute = Html::encode($this->$attribute);
    }
}