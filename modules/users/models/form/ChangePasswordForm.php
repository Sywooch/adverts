<?php

namespace app\modules\users\models\form;

use yii\base\Model;
use Yii;
use app\modules\users\models\ar\User;
use app\modules\users\UsersModule;
use app\modules\users\models\ar\AuthFail;

/**
 * @property User $user
 */
class ChangePasswordForm extends Model
{
    const SCENARIO_RESTORE_VIA_EMAIL = 'restoreViaEmail';

    /**
     * @var string
     */
    public $current_password;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $repeat_password;

    /**
     * @var User
     */
    public $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'repeat_password'], 'required'],
            ['current_password', 'required', 'except' => self::SCENARIO_RESTORE_VIA_EMAIL],
            [['password', 'repeat_password'], 'trim'],
            ['current_password', 'trim', 'except' => self::SCENARIO_RESTORE_VIA_EMAIL],
            ['repeat_password', 'compare', 'compareAttribute' => 'password'],
            ['current_password', 'validateCurrentPassword', 'except' => self::SCENARIO_RESTORE_VIA_EMAIL],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'current_password' => UsersModule::t( 'Текущий пароль'),
            'password'         => UsersModule::t('Новый пароль'),
            'repeat_password'  => UsersModule::t('Новый пароль ещё раз'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        if (parent::load($data, $formName)) {
            $this->user->load([
                'passwordNotEncrypted' => $this->password,
            ]);
            $this->user->setScenario(User::SCENARIO_CHANGE_PASSWORD);
            return true;
        }
        return false;
    }


    /**
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        $success = parent::validate($attributeNames, $clearErrors);
        if (!$this->user->validate()) {
            $success = false;
            $this->addErrors(['password' => $this->user->getErrors('passwordNotEncrypted')]);
        }
        return $success;
    }

    /**
     * Validates current password.
     */
    public function validateCurrentPassword()
    {
        if (!Yii::$app->security->validatePassword($this->current_password, $this->user->password)) {
            $this->addError('current_password', UsersModule::t('Неправильный текущий пароль'));
        }
    }
    
    /**
     * @return bool
     */
    public function changePassword()
    {
        if ($this->validate() && $this->user->save()) {
            if ($this->scenario == self::SCENARIO_RESTORE_VIA_EMAIL) {
                $this->user->passwordRestoreToken->delete();
            }
            return true;
        }
        return false;
    }
}