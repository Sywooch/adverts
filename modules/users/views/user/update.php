<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

use app\modules\users\UsersModule;
use app\modules\users\models\User;

use webvimark\extensions\BootstrapSwitch\BootstrapSwitch;

$this->title = UsersModule::t('back', 'Editing user: ') . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => UsersModule::t('back', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = UsersModule::t('back', 'Editing');

?>

<?= $this->render('_form', compact('model')) ?>