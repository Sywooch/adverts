<?php

use app\modules\users\UsersModule;

/**
 * @var yii\web\View $this
 */

$this->title = UsersModule::t('front', 'Password recovery');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="password-recovery-success">
    <div class="alert alert-success text-center">
        <?= UsersModule::t('front', 'Check your E-mail for further instructions') ?>
    </div>
</div>
