<?php

/**
 * @var yii\web\View $this
 * @var \app\modules\users\models\form\RegistrationForm $model
 */

use app\modules\core\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;
use app\modules\users\UsersModule;

$this->title = !$registeredUser ? UsersModule::t('Регистрация') : UsersModule::t('Подтвердите регистрацию');

?>

<?php if ($registeredUser): ?>

    <div class="registration-wait-for-confirmation">
        <div class="alert alert-info text-center">
            <?= UsersModule::t('На Ваш почтовый ящик <b>{email}</b> отправлено письмо с инструкциями по активации аккаунта.', 'main', [
                'email' => $registeredUser->email
            ]) ?>
        </div>
    </div>

<?php else: ?>

    <div class="col-sm-offset-2 col-md-offset-3 col-lg-offset-4 col-xs-12 col-sm-8 col-md-6 col-lg-4">
        <?php $form = ActiveForm::begin([
            'id' => 'form-registration',
            'validateOnBlur' => false,
        ]); ?>
            <?= $form->field($model, 'email', [
                'template' => '{label}<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>{input}</div>{error}'
            ])->textInput([
                'autocomplete' => 'off',
                'placeholder' => $model->getAttributeLabel('email'),
            ]); ?>

            <?= $form->field($model, 'password', [
                'template' => '{label}<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>{input}</div>{error}'
            ])->passwordInput([
                'autocomplete' => 'off',
                'placeholder' => $model->getAttributeLabel('password'),
            ]); ?>

            <?= $form->field($model, 'repeatPassword', [
                'template' => '{label}<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>{input}</div>{error}'
            ])->passwordInput([
                'autocomplete' => 'off',
                'placeholder' => $model->getAttributeLabel('repeatPassword'),
            ]); ?>

            <?php /*$form->field($model, 'captcha')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-2 col-sm-4 col-md-4 col-lg-4">{image}</div><div class="col-sm-6 col-md-6 col-lg-6">{input}</div></div>',
                'captchaAction' => ['/users/auth/captcha']
            ]);*/ ?>

            <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t('Регистрироваться'), [
                'class' => 'btn btn-primary btn-block'
            ]); ?>
        <?php ActiveForm::end(); ?>
    </div>

<?php endif; ?>