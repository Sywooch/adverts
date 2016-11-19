<?php

use yii\captcha\Captcha;
use yii\helpers\Html;

use roman444uk\yii\widgets\ActiveForm;
use app\modules\users\UsersModule;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\PasswordRecoveryForm $model
 */

$this->title = UsersModule::t('front', 'Password recovery');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ( Yii::$app->session->hasFlash('error') ): ?>
    <div class="alert-alert-warning text-center">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<?php $form = ActiveForm::begin([
    'id' => 'user',
    'validateOnBlur' => false,
    'fieldConfig' => [
        'template'=>"{input}\n{error}",
    ],
]) ?>

    <?= $form->field($model, 'email')->textInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('email'),
        'autofocus' => true
    ]) ?>

    <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
        'template' => '<div class="row"><div class="col-sm-offset-2 col-sm-4">{image}</div><div class="col-sm-4">{input}</div></div>',
        'captchaAction' => ['/users/auth/captcha']
    ]) ?>

    <?= Html::submitButton(
        '<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t('front', 'Recover'),
        ['class' => 'btn btn-primary btn-block']
    ) ?>

<?php ActiveForm::end(); ?>