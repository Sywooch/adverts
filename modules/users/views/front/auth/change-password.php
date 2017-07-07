<?php

use app\modules\core\widgets\ActiveForm;
use app\modules\users\models\form\ChangePasswordForm;
use app\modules\users\UsersModule;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\ChangeOwnPasswordForm $model
 */

$this->title = UsersModule::t('Смена пароля');

?>

<?php if ($passwordChanged): ?>
    <div class="alert alert-success text-center">
        <?= UsersModule::t('Пароль успешно изменен. Теперь Вы можете войти сновым паролем.'); ?>
    </div>
<?php else: ?>
    <div class="col-sm-offset-3 col-md-offset-4 col-sm-6 col-md-4 col-lg-4">
        <?php $form = ActiveForm::begin([
            'id' => 'user',
            'enableAjaxValidation' => true,
            'validateOnChange' => false,
            'validateOnBlur' => false,
            'fieldConfig' => [
                'template'=>"{input}\n{error}",
            ],
        ]); ?>

        <?php if ($model->scenario != ChangePasswordForm::SCENARIO_RESTORE_VIA_EMAIL): ?>
            <?= $form->field($model, 'current_password', [
                'template' => '{label}<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>{input}</div>{error}'
            ])->passwordInput([
                'maxlength' => 255,
                'placeholder' => $model->getAttributeLabel('current_password'),
                'autocomplete' => 'off',
            ]); ?>
        <?php endif; ?>

        <?= $form->field($model, 'password', [
            'template' => '{label}<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>{input}</div>{error}'
        ])->passwordInput([
            'maxlength' => 255,
            'placeholder' => $model->getAttributeLabel('password'),
            'autocomplete' => 'off'
        ]); ?>

        <?= $form->field($model, 'repeat_password', [
            'template' => '{label}<div class="input-group"><span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>{input}</div>{error}'
        ])->passwordInput([
            'maxlength' => 255,
            'placeholder' => $model->getAttributeLabel('repeat_password'),
            'autocomplete' => 'off'
        ]); ?>

        <?= Html::submitButton(
            '<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t( 'Сохранить'),
            ['class' => 'btn btn-primary btn-block']
        ); ?>

        <?php ActiveForm::end(); ?>
    </div>
<?php endif; ?>