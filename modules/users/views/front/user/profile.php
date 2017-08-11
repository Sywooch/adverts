<?php

/**
 * @var $changePasswordForm \app\modules\users\models\form\ChangePasswordForm
 * @var $model \app\modules\users\models\ar\User
 * @var $profile \app\modules\users\models\ar\Profile
 * @var $this \yii\web\View
 */

?>

<?= $this->render('_form', compact('model', 'changePasswordForm', 'profile')) ?>