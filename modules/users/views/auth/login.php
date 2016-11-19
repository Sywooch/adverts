<?php

/**
 * @var $this yii\web\View
 * @var $model webvimark\modules\UserManagement\models\forms\LoginForm
 */

use roman444uk\yii\widgets\ActiveForm;
use yii\helpers\Html;

use app\modules\users\components\GhostHtml;
use app\modules\users\UsersModule;

$this->title = UsersModule::t('front', 'Authorization');

?>

<?php $form = ActiveForm::begin([
    'id'      => 'form-login',
    'options' => [
        'autocomplete' => 'off'
    ],
    'validateOnBlur' => false,
    'fieldConfig' => [
        'template'=>"{input}\n{error}",
    ],
]) ?>

    <?= $form->field($model, 'username')->textInput([
        'placeholder' => $model->getAttributeLabel('username'),
        'autocomplete' => 'off'
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput([
        'placeholder' => $model->getAttributeLabel('password'),
        'autocomplete' => 'off'
    ]) ?>

    <?= $form->field($model, 'rememberMe')->checkbox([
        'value' => true
    ]) ?>

    <?= Html::submitButton(
        UsersModule::t('front', 'Login'),
        ['class' => 'btn btn-primary btn-block']
    ) ?>

    <div class="row registration-block">
        <div class="col-sm-6">
            <?= GhostHtml::a(
                UsersModule::t('front', "Registration"),
                ['/user/auth/registration']
            ) ?>
        </div>
        <div class="col-sm-6 text-right">
            <?= GhostHtml::a(
                UsersModule::t('front', "Forgot password?"),
                ['/user/auth/password-recovery']
            ) ?>
        </div>
    </div>

<?php ActiveForm::end() ?>