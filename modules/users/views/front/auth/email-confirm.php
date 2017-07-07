<?php

use app\modules\users\UsersModule;
use app\modules\users\models\ar\EmailConfirmToken;

/**
 * @var \app\modules\users\models\ar\User $model
 * @var yii\web\View $this
 */

$this->title = UsersModule::t('front', 'Подтверждение почтового ящика');

?>

<?php if ($model->emailConfirmToken && $model->emailConfirmToken->subject == EmailConfirmToken::SUBJECT_REGISTRATION): ?>
    <div class="alert alert-info text-center">
        <?= UsersModule::t('Теперь Вы зарегистрированы. Ваш почтовый ящик: <b>{email}</b>. Добро пожаловать!', 'main', [
            'email' => $model->email,
        ]); ?>
    </div>
<?php elseif ($model->emailConfirmToken && $model->emailConfirmToken->subject == EmailConfirmToken::SUBJECT_CHANGE_EMAIL): ?>
    <div class="alert alert-info text-center">
        <?= UsersModule::t('Ваш почтовый ящик теперь: <b>{email}</b>.', 'main', [
            'email' => $model->email,
        ]); ?>
    </div>
<?php else: ?>
    <div class="alert alert-warning text-center">
        Произошла ошибка!
    </div>
<?php endif; ?>