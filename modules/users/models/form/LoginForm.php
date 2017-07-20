<?php

namespace app\modules\users\models\form;

use app\modules\users\UsersModule;
use app\modules\users\models\ar\AuthFail;
use app\modules\users\models\ar\User;
use yii\base\Model;
use Yii;

class LoginForm extends Model
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
     * @var bool
     */
    public $rememberMe = false;

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
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['email', 'validateEmailConfirmed'],
            ['email', 'validateAuthFail', 'skipOnError' => false],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'      => UsersModule::t('Почтовый ящик'),
            'password'   => UsersModule::t('Пароль'),
            'rememberMe' => UsersModule::t('Запомнить'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        if ($timeout = AuthFail::getTimeout(AuthFail::ACTION_LOGIN, Yii::$app->request->userIP, $this->email)) {
            $this->addError('email', UsersModule::t('Вы часто ошибались при попытке войти. Подождите {timeout} секунд.', null, [
                'timeout' => $timeout
            ]));
            return false;
        }

        return parent::validate($attributeNames, $clearErrors);
    }


    /**
     * User authorization.
     * @return boolean whether authorization run successful
     */
    public function login()
    {
        if ($this->validate()) {
            AuthFail::resetCounters(AuthFail::ACTION_LOGIN, Yii::$app->request->userIP, $this->email);
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Password validation.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('password', UsersModule::t('Неправильное имя или пароль'));
            }
        }
    }

    /**
     * Checking whether user has activate his account.
     */
    public function validateEmailConfirmed($attribute, $model)
    {
        if (!$this->hasErrors() && ($user = $this->getUser()) && ($user->status == $user::STATUS_INACTIVE)) {
            $this->addError($attribute, UsersModule::t('Ваш аккаунт не активирован. Проверьте Ваш почтовый ящик, на него должны были прийти инструкции по активации.'));
        }
    }

    /**
     * Checking whether login attempt was unsuccessful.
     */
    public function validateAuthFail()
    {
        if ($this->hasErrors()) {
            AuthFail::create(AuthFail::ACTION_LOGIN, Yii::$app->request->userIP, $this->email);
        }
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }
}
