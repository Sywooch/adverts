<?php

/**
 * @var yii\web\View $this
 * @var \app\modules\users\models\form\LoginForm $model
 */

use yii\bootstrap\Html;
use app\modules\authclient\widgets\AuthChoice;
use app\modules\core\widgets\ActiveForm;
use app\modules\users\UsersModule;

$this->title = UsersModule::t('Вход');

?>

<div class="col-sm-offset-2 col-md-offset-3 col-lg-offset-4 col-xs-12 col-sm-8 col-md-6 col-lg-4">
    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'enableAjaxValidation' => true,
        'validateOnChange' => false,
        'validateOnBlur' => false
    ]) ?>

        <?= AuthChoice::widget([
            'baseAuthUrl' => ['/users/auth/client']
        ]); ?>

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