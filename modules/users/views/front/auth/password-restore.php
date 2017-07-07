<?php

use yii\captcha\Captcha;
use yii\helpers\Html;

use app\modules\users\UsersModule;
use app\modules\core\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var \app\modules\users\models\form\PasswordRestoreForm $model
 */

$this->title = UsersModule::t('Восстановление пароля','front');

?>

<?php if ($sendingError): ?>
    <div class="alert alert-warning text-center">
        <?= UsersModule::t('Ошибка отправки письма. Попробуйте, пожалуйста, еще раз.','front'); ?>
    </div>
<?php elseif (Yii::$app->session->hasFlash('passwordRestoreEmailSend')): ?>
    <div class="alert alert-info text-center">
        <?= UsersModule::t('На указанный Вами почтовый ящик отправлено письмо с инструкциями по восстановлению пароля.','front'); ?>
    </div>
<?php else: ?>
    <div class="col-md-offset-4 col-md-4">
        <?php $form = ActiveForm::begin([
            'id' => 'password-restore-form',
            'enableAjaxValidation' => true,
            'validateOnChange' => false,
            'validateOnBlur' => false
        ]); ?>

            <?= $form->field($model, 'email', [
                'template' => '{label}<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>{input}</div>{error}'
            ])->textInput([
                'autocomplete' => 'off',
                'placeholder' => $model->getAttributeLabel('email')
            ]); ?>

            <?php /*$form->field($model, 'captcha')->widget(Captcha::className(), [
                'template' => '<div class="row"><div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-2 col-sm-4 col-md-4 col-lg-4">{image}</div><div class="col-sm-6 col-md-6 col-lg-6">{input}</div></div>',
                'captchaAction' => ['/users/auth/captcha'],
                'value' => ''
            ]);*/ ?>

            <?= Html::submitButton('<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t('Отправить письмо'), [
                'class' => 'btn btn-primary btn-block'
            ]); ?>

        <?php ActiveForm::end(); ?>
    </div>
<?php endif; ?>