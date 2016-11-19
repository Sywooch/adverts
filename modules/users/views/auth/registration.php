<?php

use roman444uk\yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

use app\modules\users\UsersModule;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\forms\RegistrationForm $model
 */

$this->title = UsersModule::t('front', 'Registration');
$this->params['breadcrumbs'][] = $this->title;

?>
    
<?php $form = ActiveForm::begin([
    'id' => 'form-registration',
    'validateOnBlur' => false,
    'fieldConfig' => [
        'template' => "{input}\n{error}",
    ],
]); ?>

    <?= $form->field($model, 'username')->textInput([
        'placeholder' => $model->getAttributeLabel('username'),
        'maxlength' => 50,
        'autocomplete' => 'off',
        'autofocus' => true
    ]) ?>

    <?= $form->field($model, 'password')->passwordInput([
        'placeholder' => $model->getAttributeLabel('password'),
        'maxlength' => 255,
        'autocomplete' => 'off'
    ]) ?>

    <?= $form->field($model, 'repeat_password')->passwordInput([
        'placeholder' => $model->getAttributeLabel('repeat_password'),
        'maxlength' => 255,
        'autocomplete' => 'off'
    ]) ?>

    <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
        'template' => '<div class="row"><div class="col-sm-offset-2 col-sm-4">{image}</div><div class="col-sm-4">{input}</div></div>',
        'captchaAction' => ['/users/auth/captcha']
    ]) ?>

    <?= Html::submitButton(
        '<span class="glyphicon glyphicon-ok"></span> ' . UsersModule::t('front', 'Register'),
        [
            'class' => 'btn btn-primary btn-block'
        ]
    ) ?>

<?php ActiveForm::end(); ?>