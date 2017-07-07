<?php

use app\modules\users\UsersModule;

/**
 * @var $changePasswordForm \app\modules\users\models\form\ChangePasswordForm
 * @var $model \app\modules\users\models\ar\User
 * @var $profile \app\modules\users\models\ar\Profile
 * @var $this \yii\web\View
 */

$this->title = UsersModule::t('Профиль') . ' ' . $model->profile->fullName;

?>

<?= $this->render('_form', compact('model', 'changePasswordForm', 'profile')) ?>