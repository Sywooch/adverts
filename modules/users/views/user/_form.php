<?php

use yii\helpers\Html;
use yii\helpers\Url;

use roman444uk\yii\widgets\ActiveForm;

use app\modules\users\models\User;
use app\modules\users\UsersModule;

use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;

?>

<div class="user-form">
    <?php $form = ActiveForm::begin([
        'id' => 'user-form',
        'enableClientValidation' => true,
        'enableAjaxValidation' => true,
        'validateOnBlur' => true,
        'ajaxSubmit' => Yii::$app->request->isAjax ? true : false,
        'validationUrl' => Url::to(['validate', 'id' => $model->id])
    ]) ?>
        
        <?= $form->field($model->loadDefaultValues(), 'status')
            ->dropDownList(User::getStatusList()) ?>

        <?= $form->field($model, 'username')
            ->textInput(['maxlength' => 255, 'autocomplete'=>'off']) ?>

        <?php if ($model->isNewRecord): ?>
            <?= $form->field($model, 'password')
                ->passwordInput(['maxlength' => 255, 'autocomplete'=>'off']) ?>

            <?= $form->field($model, 'repeat_password')
                ->passwordInput(['maxlength' => 255, 'autocomplete'=>'off']) ?>
        <?php endif; ?>


        <?php if (User::hasPermission('bindUserToIp')): ?>
            <?= $form->field($model, 'bind_to_ip')
                    ->textInput(['maxlength' => 255])
                    ->hint(UsersModule::t('back','For example: 123.34.56.78, 168.111.192.12')) ?>
        <?php endif; ?>

        <?php if (User::hasPermission('editUserEmail')): ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

            <?= $form->field($model, 'email_confirmed')->checkbox() ?>
        <?php endif; ?>


        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <?php if ($model->isNewRecord): ?>
                    <?= Html::submitButton(
                        '<span class="glyphicon glyphicon-plus-sign"></span> '
                            . UsersModule::t('back', 'Create'),
                        ['class' => 'btn btn-success']
                    ) ?>
                <?php else: ?>
                    <?= Html::submitButton(
                        '<span class="glyphicon glyphicon-ok"></span> '
                            . UsersModule::t('back', 'Save'),
                        ['class' => 'btn btn-primary btn-sm']
                    ) ?>
                <?php endif; ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>

<?php //BootstrapSwitch::widget() ?>