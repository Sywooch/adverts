<?php

namespace app\modules\users\models\forms;

use yii\base\Model;
use Yii;

use app\modules\users\models\User;
use app\modules\users\UsersModule;

class ConfirmEmailForm extends Model
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var string
     */
    public $email;

    /**
     * Remove confirmation token if it's expiration date is over
     */
    public function init()
    {
        if ($this->user->confirmation_token !== null AND $this->getTokenTimeLeft() == 0) {
            $this->user->removeConfirmationToken();
            $this->user->save(false);
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'validateEmailConfirmedUnique'],
        ];
    }

    /**
     * Check that there is no such confirmed E-mail in the system
     */
    public function validateEmailConfirmedUnique()
    {
        if ($this->email) {
            $exists = User::findOne([
                'email'=>$this->email,
                'email_confirmed'=>1,
            ]);

            if ($exists) {
                $this->addError('email', UsersModule::t('front', 'This E-mail already exists'));
            }
        }
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
        ];
    }

    /**
     *
     *
     * @param bool $inMinutes
     *
     * @return int
     */
    public function getTokenTimeLeft($inMinutes = false)
    {
        if ($this->user AND $this->user->confirmation_token) {
            $expire = Yii::$app->getModule('users')->confirmationTokenExpire;
            $parts = explode('_', $this->user->confirmation_token);
            $timestamp = (int)end($parts);
            
            $timeLeft = $timestamp + $expire - time();
            
            if ($timeLeft < 0) {
                return 0;
            }
            
            return $inMinutes ? round($timeLeft / 60) : $timeLeft;
        }
        
        return 0;
    }

    /**
     * @param bool $performValidation
     *
     * @return bool
     */
    public function sendEmail($performValidation = true)
    {
        if ($performValidation AND !$this->validate()) {
            return false;
        }

        $this->user->email = $this->email;
        $this->user->generateConfirmationToken();
        $this->user->save(false);

        return Yii::$app->mailer->compose(Yii::$app->getModule('users')->mailerOptions['confirmEmailFormViewFile'], ['user' => $this->user])
            ->setFrom(Yii::$app->getModule('users')->mailerOptions['from'])
            ->setTo($this->email)
            ->setSubject(UsersModule::t('front', 'E-mail confirmation for') . ' ' . Yii::$app->name)
            ->send();
    }
}