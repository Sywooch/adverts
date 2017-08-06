<?php

/**
 * @var yii\web\View $this
 * @var \app\modules\users\models\form\LoginForm $model
 */

use yii\bootstrap\Html;
use app\modules\core\widgets\ActiveForm;
use app\modules\users\UsersModule;

$this->title = UsersModule::t('Вход');

?>

<div class="col-sm-offset-3 col-md-offset-4 col-sm-6 col-md-4 col-lg-4">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false
    ]) ?>

        <?= \yii\authclient\widgets\AuthChoice::widget([
            'baseAuthUrl' => ['/users/auth/client']
        ]); ?>

        <!--<div class="row" style="padding-top: 10px; padding-bottom: 10px;">
            <div class="col-sm-12 col-md-12 col-lg-12 text-center">
                <?= Html::a('', ['/eauth/auth/login', 'service' => 'vkontakte'], [
                    'class' => 'icon vkontakte',
                    'title' => 'vk.com'
                ]) ?>

                <?= Html::a('', ['/eauth/auth/login', 'service' => 'facebook'], [
                    'class' => 'icon facebook',
                    'title' => 'facebook.com'
                ]) ?>

                <?= Html::a('', ['/eauth/auth/login', 'service' => 'odnoklassniki'], [
                    'class' => 'icon odnoklassniki',
                    'title' => 'ok.ru'
                ]) ?>

                <?= Html::a('', ['/eauth/auth/login', 'service' => 'twitter'], [
                    'class' => 'icon twitter',
                    'title' => 'twitter.com'
                ]) ?>

                <?= Html::a('', ['/eauth/auth/login', 'service' => 'mailru'], [
                    'class' => 'icon mailru',
                    'title' => 'mail.ru'
                ]) ?>

                <?= Html::a('', ['/eauth/auth/login', 'service' => 'google_oauth'], [
                    'class' => 'icon google',
                    'title' => 'google.com'
                ]) ?>
            </div>
        </div>-->

        <?= $form->field($model, 'email', [
            'template' => '{label}<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>{input}</div>{error}'
        ])->textInput([
            'autocomplete' => 'off',
            'placeholder' => $model->getAttributeLabel('email'),
            'class' => 'form-control input-sm'
        ]); ?>

        <?= $form->field($model, 'password', [
            'template' => '{label}<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>{input}</div>{error}'
        ])->passwordInput([
            'autocomplete' => 'off',
            'placeholder' => $model->getAttributeLabel('password'),
            'class' => 'form-control input-sm'
        ]); ?>

        <div class="row">
            <div class="col-sm-5 col-md-5 col-lg-5">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'value' => true
                ]) ?>
            </div>
            <div class="col-sm-7 col-md-7 col-lg-7 text-right" style="padding-top: 10px; padding-bottom: 10px;">
                <?= Html::a(UsersModule::t('Забыли пароль?'), ['/users/auth/password-restore']); ?>
            </div>
        </div>

        <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t('Войти', 'front'), [
            'class' => 'btn btn-primary btn-block'
        ]); ?>

        <div class="row" style="padding-top: 15px; padding-bottom: 10px;">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <?= UsersModule::t('Вы зарегистрированы'); ?>? <?= Html::a(UsersModule::t('Регистрация'), ['/users/auth/registration']) ?>
            </div>
        </div>

    <?php ActiveForm::end() ?>
</div>