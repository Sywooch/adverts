<?php

use yii\helpers\Html;

use roman444uk\yii\widgets\ActiveForm;

use app\modules\users\UsersModule;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\ConfirmEmailForm $model
 */

$this->title = UsersModule::t('front', 'Confirm E-mail');
$this->params['breadcrumbs'][] = $this->title;

?>
                        
<?php if ( Yii::$app->session->hasFlash('error') ): ?>
    <div class="alert alert-warning text-center">
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>

<?php if ($model->user->confirmation_token === null): ?>
    <?php $form = ActiveForm::begin([
        'id' => 'user',
        'validateOnBlur' => false,
        'fieldConfig' => [
            'template'=>"{input}\n{error}",
        ],
    ]); ?>

        <?= $form->field($model, 'email')->textInput([
            'placeholder' => $model->getAttributeLabel('email'),
            'maxlength' => 255,
            'autofocus' => true
        ]) ?>

        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t('front', 'Confirm'),
            ['class' => 'btn btn-primary btn-block']
        ) ?>

        <?php ActiveForm::end(); ?>
<?php else: ?>
    <div class="alert alert-info text-center">
        <?= UsersModule::t('back', 'E-mail with activation link has been sent to <b>{email}</b>. This link will expire in {minutes} min.', [
            'email' => $model->user->email,
            'minutes' => $model->getTokenTimeLeft(true),
        ]) ?>
    </div>
<?php endif; ?>