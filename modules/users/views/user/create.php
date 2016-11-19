<?php

use app\modules\users\UsersModule;

use yii\helpers\Html;

$this->title = UsersModule::t('back', 'User creation');
$this->params['breadcrumbs'][] = ['label' => UsersModule::t('back', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->render('_form', compact('model')) ?>