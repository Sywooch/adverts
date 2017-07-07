<?php

/**
 * @var $user \app\modules\users\models\ar\User
 * @var $this yii\web\View
 */

use yii\helpers\Html;
use app\modules\users\UsersModule;

?>

Для того, чтобы изменить пароль, перейдите по следующей <?= Html::a(UsersModule::t('ссылке'), Yii::$app->urlManager->createAbsoluteUrl([
    '/users/auth/change-password', 'token' => $user->passwordRestoreToken->token
])); ?>