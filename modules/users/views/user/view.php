<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

use app\modules\users\components\GhostHtml;
use app\modules\users\models\rbacDB\Role;
use app\modules\users\models\User;
use app\modules\users\UsersModule;

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => UsersModule::t('back', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="panel panel-default">
    
    <div class="panel-heading">
        <h3 class="panel-title"><?= $this->title ?></h3>
    </div>
    
    <div class="panel-body">
        <?php if (!Yii::$app->request->isAjax): ?>
            <p>
                <?= GhostHtml::a(UsersModule::t('back', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-sm btn-primary']) ?>
                <?= GhostHtml::a(UsersModule::t('back', 'Create'), ['create'], ['class' => 'btn btn-sm btn-success']) ?>
                <?= GhostHtml::a(
                        UsersModule::t('back', 'Roles and permissions'),
                        ['/user/user-permission/set', 'id'=>$model->id],
                        ['class' => 'btn btn-sm btn-default']
                ) ?>
                <?= GhostHtml::a(UsersModule::t('back', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-danger pull-right',
                    'data' => [
                        'confirm' => UsersModule::t('back', 'Are you sure you want to delete this user?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        <?php endif ?>

        <?= DetailView::widget([
            'model'      => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'status',
                    'value' => User::getStatusValue($model->status),
                ],
                'username',
                [
                    'attribute' => 'email',
                    'value' => $model->email,
                    'format' => 'email',
                    'visible' => User::hasPermission('viewUserEmail'),
                ],
                [
                    'attribute' => 'email_confirmed',
                    'value' => $model->email_confirmed,
                    'format' => 'boolean',
                    'visible' => User::hasPermission('viewUserEmail'),
                ],
                [
                    'label' => UsersModule::t('back', 'Roles'),
                    'value' => implode('<br>', ArrayHelper::map(
                            Role::getUserRoles($model->id), 'name', 'description'
                    )),
                    'visible' => User::hasPermission('viewUserRoles'),
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'bind_to_ip',
                    'visible' => User::hasPermission('bindUserToIp'),
                ],
                array(
                    'attribute' => 'registration_ip',
                    'value' => Html::a($model->registration_ip, "http://ipinfo.io/" . $model->registration_ip, ["target"=>"_blank"]),
                    'format' => 'raw',
                    'visible' => User::hasPermission('viewRegistrationIp'),
                ),
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
    </div>
</div>
