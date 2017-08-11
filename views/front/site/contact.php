<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\form\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$isGuest = Yii::$app->user->isGuest;

?>
<div class="site-contact">

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            Thank you for contacting us. We will respond to you as soon as possible.
        </div>

        <p>
            Note that if you turn on the Yii debugger, you should be able
            to view the mail message on the mail panel of the debugger.
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                Because the application is in development mode, the email is not sent but saved as
                a file under <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                Please configure the <code>useFileTransport</code> property of the <code>mail</code>
                application component to be false to enable email sending.
            <?php endif; ?>
        </p>

    <?php else: ?>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-8 col-md-offset-1 col-lg-offset-2">

                <div class="alert alert-info text-center">
                    Если у Вас есть вопросы, предложения или пожелания, то вы можете изложить их написав по адресу:<br>
                    <b><?= Yii::$app->params['adminEmail']; ?></b><br>
                    либо заполнить форму ниже, и мы обязательно ответим. <b>Благодарим за внимание!</b>
                </div>

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?php if ($isGuest): ?>
                        <?= $form->field($model, 'name')->textInput([
                            'autofocus' => true
                        ]); ?>

                        <?= $form->field($model, 'email'); ?>
                    <?php endif; ?>

                    <?= $form->field($model, 'subject'); ?>

                    <?= $form->field($model, 'body')->textarea([
                        'rows' => 6
                    ]); ?>

                    <?php if (false): ?>
                        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                            'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                        ]); ?>
                    <?php endif; ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Отправить сообщение'), [
                            'class' => 'btn btn-primary',
                            'name' => 'contact-button'
                        ]); ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
