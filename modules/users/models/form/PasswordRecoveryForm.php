<?php
namespace app\modules\users\models\forms;

use yii\base\Model;
use Yii;

use app\modules\users\models\User;
use app\modules\users\UsersModule;

class PasswordRecoveryForm extends Model
{
    /**
     * @var object User
     */
    protected $user;

    /**
     * @var string email
     */
    public $email;

    /**
     * @var string captcha
     */
    public $captcha;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['captcha', 'captcha', 'captchaAction' => '/users/auth/captcha'],
            [['email', 'captcha'], 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'validateEmailConfirmedAndUserActive'],
        ];
    }
    
    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'email' => 'E-mail',
            'captcha' => UsersModule::t('front', 'Captcha'),
        ];
    }

    /**
     * @return bool
     */
    public function validateEmailConfirmedAndUserActive()
    {
        if (!Yii::$app->getModule('users')->checkAttempts()) {
            $this->addError('email', UserManagementModule::t('front', 'Too many attempts'));
            
            return false;
        }

        $user = User::findOne([
            'email' => $this->email,
            'email_confirmed' => 1,
            'status' => User::STATUS_ACTIVE,
        ]);

        if ($user) {
            $this->user = $user;
        } else {
            $this->addError('email', UsersModule::t('front', 'E-mail is invalid'));
        }
    }

    /**
     * @param bool $performValidation
     *
     * @return bool whether email sended successfull
     */
    public function sendEmail($performValidation = true)
    {
        if ( $performValidation AND !$this->validate() ) {
            return false;
        }

        $this->user->generateConfirmationToken();
        $this->user->save(false);

        return Yii::$app->mailer->compose(Yii::$app->getModule('users')->mailerOptions['passwordRecoveryFormViewFile'], ['user' => $this->user])
            ->setFrom(Yii::$app->getModule('users')->mailerOptions['from'])
            ->setTo($this->email)
            ->setSubject(UsersModule::t('front', 'Password reset for') . ' ' . Yii::$app->name)
            ->send();
    }
}
