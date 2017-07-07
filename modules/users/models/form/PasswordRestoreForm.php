<?php

namespace app\modules\users\models\form;

use app\modules\users\models\ar\PasswordRestoreToken;
use app\modules\users\models\ar\User;
use app\modules\users\UsersModule;
use app\modules\users\models\ar\AuthFail;
use yii\base\Model;
use Yii;

class PasswordRestoreForm extends Model
{
    /**
     * @var string email
     */
    public $email;

    /**
     * @var User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'validateEmailConfirmedAndUserActive'],
            ['email', 'validateTokenExists'],
            ['email', 'validateAuthFail', 'skipOnError' => false],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => UsersModule::t('Почтовый ящик'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($timeout = AuthFail::getTimeout(AuthFail::ACTION_PASSWORD_RESTORE, Yii::$app->request->userIP, $this->email)) {
            $this->addError('email', UsersModule::t('Вы часто ошибались. Подождите {timeout} секунд.', null, [
                'timeout' => $timeout
            ]));
            return false;
        }

        return parent::validate($attributeNames, $clearErrors);
    }

    /**
     * Проверка подтверждения пользователем пароля и что пользователь активен.
     */
    public function validateEmailConfirmedAndUserActive()
    {
        if ($user = User::findOne(['email' => $this->email, 'status' => User::STATUS_ACTIVE])) {
            $this->_user = $user;
        } else {
            $this->addError('email', UsersModule::t('Пользователь с таким почтовым ящиком не зарегистрирован'));
        }
    }

    /**
     * Checking whether user already has password restore token.
     */
    public function validateTokenExists($attribute)
    {
        if (PasswordRestoreToken::findOne(['user_id' => $this->_user->id])) {
            $this->addError($attribute, UsersModule::t('Вы уже отправляли запрос на изменение пароля. Пожалуйста, проверьте почту.'));
        }
    }

    /**
     * Checking whether login attempt was unsuccessful.
     */
    public function validateAuthFail()
    {
        if ($this->hasErrors()) {
            AuthFail::create(AuthFail::ACTION_PASSWORD_RESTORE, Yii::$app->request->userIP, $this->email);
        }
    }

    /**
     * Sending restore password email.
     * @return bool
     */
    public function sendEmail()
    {
        AuthFail::resetCounters(AuthFail::ACTION_PASSWORD_RESTORE, Yii::$app->request->userIP, $this->email);
        $this->_user->generatePasswordRestoreToken();

        $params = Yii::$app->params;
        return Yii::$app->mailer->compose('@app/mail/front/users/password-restore', ['user' => $this->_user])
            ->setFrom($params['adminEmail'])
            ->setTo($this->email)
            ->setSubject(UsersModule::t('Восстановление пароля для ') . ' ' . Yii::$app->name)
            ->send();
    }
}
