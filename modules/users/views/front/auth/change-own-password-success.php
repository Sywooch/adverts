<?php

use app\modules\users\UsersModule;

/**
 * @var yii\web\View $this
 */

$this->title = UsersModule::t('back', 'Change own password');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="change-own-password-success">
    <div class="alert alert-success text-center">
        <?= UsersModule::t('back', 'Password has been changed') ?>
    </div>
</div>
