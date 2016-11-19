<?php

use app\modules\users\UsersModule;
use roman444uk\yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\ChangeOwnPasswordForm $model
 */

$this->title = UsersModule::t('back', 'Change own password');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php if ( Yii::$app->session->hasFlash('success') ): ?>
        <div class="alert alert-success text-center">
                <?= Yii::$app->session->getFlash('success') ?>
        </div>
<?php endif; ?>

<?php $form = ActiveForm::begin([
    'id' => 'user',
    'validateOnBlur' => false,
    'fieldConfig' => [
        'template'=>"{input}\n{error}",
    ],
]); ?>

    <?php if ($model->scenario != 'restoreViaEmail' ): ?>
        <?= $form->field($model, 'current_password')->passwordInput([
            'maxlength' => 255,
            'placeholder' => $model->getAttributeLabel('current_password'),
            'autocomplete' => 'off',
        ]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'password')->passwordInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('password'),
        'autocomplete' => 'off'
    ]) ?>

    <?= $form->field($model, 'repeat_password')->passwordInput([
        'maxlength' => 255,
        'placeholder' => $model->getAttributeLabel('repeat_password'),
        'autocomplete' => 'off'
    ]) ?>

    <?= Html::submitButton(
        '<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t('back', 'Save'),
        ['class' => 'btn btn-primary btn-block']
    ) ?>

<?php ActiveForm::end(); ?>