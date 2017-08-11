<?php

use app\modules\core\widgets\ActiveForm;
use app\modules\users\UsersModule;
use yii\helpers\Html;

/**
 * @var $changePasswordForm \app\modules\users\models\form\ChangePasswordForm
 * @var $model \app\modules\users\models\ar\User
 * @var $profile \app\modules\users\models\ar\Profile
 * @var $this \yii\web\View
 */

$formFieldTemplate = "
    <div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4 text-left-xs text-right\">{label}</div>
    <div class=\"col-xs-12 col-sm-4 col-md-4 col-lg-4\">{input}</div>
    <div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center\">{error}</div>
";

?>

<?php if (Yii::$app->session->getFlash('passwordChangedSuccess')): ?>
    <div class="alert alert-success text-center">
        <?= UsersModule::t('Пароль успешно изменен'); ?>
    </div>
<?php endif; ?>

<!-- Change password form -->
<?php $form = ActiveForm::begin([
    'id' => 'change-password-form',
    'enableAjaxValidation' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'fieldConfig' => [
        'template' => $formFieldTemplate
    ]
]); ?>

    <div class="row mt-10">
        <?= $form->field($changePasswordForm, 'current_password')->passwordInput([
            'maxlength' => 255,
            'autocomplete' => 'off',
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($changePasswordForm, 'password')->passwordInput([
            'maxlength' => 255,
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($changePasswordForm, 'repeat_password')->passwordInput([
            'maxlength' => 255,
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <div class="col-xs-offset-4 col-sm-offset-4 col-md-offset-4 col-lg-offset-4 col-xs-6 col-sm-4 col-md-4 col-md-4 col-lg-4">
            <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t('Изменить пароль'), [
                'class' => 'btn btn-primary btn-block'
            ]); ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>

<hr>

<?php if (Yii::$app->session->getFlash('profileChangedSuccess')): ?>
    <div class="alert alert-success text-center">
        <?= UsersModule::t('Профиль успешно изменен'); ?>
    </div>
<?php endif; ?>

<!-- Profile form -->
<?php $form = ActiveForm::begin([
    'id' => 'profile-form',
    'enableAjaxValidation' => true,
    'validateOnChange' => false,
    'validateOnBlur' => false,
    'fieldConfig' => [
        'template' => $formFieldTemplate
    ],
]); ?>

    <div class="row mt-10">
        <?= $form->field($profile, 'phone_1')->textInput([
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($profile, 'phone_2')->textInput([
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($profile, 'phone_3')->textInput([
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($profile, 'skype')->textInput([
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($profile, 'isq')->textInput([
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($profile, 'page_vk')->textInput([
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($profile, 'page_fb')->textInput([
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($profile, 'page_ok')->textInput([
            'autocomplete' => 'off'
        ]); ?>
    </div>

    <div class="row mt-10">
        <div class="col-xs-offset-4 col-sm-offset-4 col-md-offset-4 col-lg-offset-4 col-xs-6 col-sm-4 col-md-4 col-md-4 col-lg-4">
            <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t('Сохранить'), [
                'class' => 'btn btn-primary btn-block'
            ]); ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>
