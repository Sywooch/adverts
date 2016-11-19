<?php

use app\modules\users\UsersModule;

/**
 * @var yii\web\View $this
 * @var webvimark\modules\UserManagement\models\User $user
 */

$this->title = UserManagementModule::t('front', 'E-mail confirmed');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="change-own-password-success">
    <div class="alert alert-success text-center">
        <?= UsersModule::t('front', 'E-mail confirmed') ?> - <b><?= $user->email ?></b>
    </div>
</div>
