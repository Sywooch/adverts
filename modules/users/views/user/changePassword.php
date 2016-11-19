<?php

use roman444uk\yii\widgets\ActiveForm;
use yii\helpers\Html;

use app\modules\users\UsersModule;

$this->title = UsersModule::t('back', 'Changing password for user: ') . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => UsersModule::t('back', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = UsersModule::t('back', 'Changing password');

?>
<div class="user-update">
    <h2 class="lte-hide-title"><?= $this->title ?></h2>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="user-form">
                <?php $form = ActiveForm::begin([
                    'id' => 'user',
                    'enableClientValidation' => true,
                    'enableAjaxValidation' => true,
                    'clientEvents' => [
                    'beforeSubmit' => "function(e) {
    var dialog = $(e.target).parents('.ui-dialog .ui-dialog-content');
    if (dialog.length) {
        dialog.dialog('close');
        return false;
    }
}"
                    ]
                ]) ?>

                <?= $form->field($model, 'password')
                    ->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>

                <?= $form->field($model, 'repeat_password')
                    ->passwordInput(['maxlength' => 255, 'autocomplete' => 'off']) ?>


                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <?php if ( $model->isNewRecord ): ?>
                            <?= Html::submitButton(
                                '<span class="glyphicon glyphicon-plus-sign"></span> '
                                    . UsersModule::t('back', 'Create'),
                                ['class' => 'btn btn-success']
                            ) ?>
                        <?php else: ?>
                            <?= Html::submitButton(
                                '<span class="glyphicon glyphicon-ok"></span> '
                                    . UsersModule::t('back', 'Save'),
                                ['class' => 'btn btn-primary']
                            ) ?>
                        <?php endif; ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>