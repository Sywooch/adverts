<?php

use roman444uk\magnificPopup\MagnificPopup;
use app\modules\users\components\GhostHtml;
use app\modules\users\models\rbacDB\Role;
use app\modules\users\models\User;
use app\modules\users\UsersModule;
use roman444uk\yii\grid\GridView;
use roman444uk\yii\grid\GridBulkActions;
use roman444uk\yii\widgets\WidgetPageSize;
use roman444uk\yii\widgets\jui\LinkAjaxDialog;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\Pjax;

$this->title = UsersModule::t('back', 'Users');
$this->params['breadcrumbs'][] = $this->title;

?>
        
<?php Pjax::begin(['id' => 'user-grid-pjax']) ?>

    <?= GridView::widget([
        'id' => 'user-grid',
        'dataProvider' => $dataProvider,
        'itemsOrderDesc' => true,
        'pager' => [
            'options' => [
                'class' => 'pagination pagination-sm'
            ],
            'hideOnSinglePage' => true,
            'lastPageLabel' => '>>',
            'firstPageLabel' => '<<',
        ],
        'filterModel' => $searchModel,
        'layout' => '
            <div class="row">
                <div class="col-sm-4">
                    ' . GhostHtml::a(
                        '<span class="glyphicon glyphicon-plus-sign"></span> ' . UsersModule::t('back', 'Create'),
                        ['/users/user/create'],
                        [
                            'id' => 'user-grid-create-button',
                            'class' => 'btn btn-success btn-sm'
                        ]
                    ) . '
                </div>
                <div class="col-sm-4 text-center">
                    {summary}
                </div>
                <div class="col-sm-4 text-right">
                    ' . WidgetPageSize::widget(['pjaxId' => 'user-grid-pjax']) . '
                </div>
            </div>
            {items}
            <div class="row">
                <div class="col-sm-8">
                    {pager}
                </div>
                <div class="col-sm-4 text-right" style="padding: 20px">
                    ' . GridBulkActions::widget(['gridId' => 'user-grid']) . '
                </div>
            </div>
        ',
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'options' => [
                    'style' => 'width:10px'
                ]
            ],
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => [
                    'style'=>'width:10px'
                ]
            ],
            [
                'class' => 'roman444uk\yii\grid\StatusColumn',
                'attribute' => 'superadmin',
                'visible' => Yii::$app->user->isSuperadmin,
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],
            [
                'attribute' => 'username',
                'value' => function(User $model) {
                    return Html::a(
                        $model->username,
                        ['view', 'id' => $model->id],
                        ['data-pjax' => 0]
                    );
                },
                'format' => 'raw',
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],
            [
                'attribute' => 'email',
                'format' => 'raw',
                'visible' => User::hasPermission('viewUserEmail'),
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],
            [
                'class' => 'roman444uk\yii\grid\StatusColumn',
                'attribute' => 'email_confirmed',
                'visible' => User::hasPermission('viewUserEmail'),
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],
            [
                'attribute' => 'gridRoleSearch',
                'filter' => ArrayHelper::map(Role::getAvailableRoles(Yii::$app->user->isSuperAdmin),'name', 'description'),
                'value' => function(User $model) {
                    return implode(', ', ArrayHelper::map($model->roles, 'name', 'description'));
                },
                'format' => 'raw',
                'visible' => User::hasPermission('viewUserRoles'),
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],
            [
                'attribute' => 'registration_ip',
                'value' => function(User $model) {
                    return Html::a($model->registration_ip, "http://ipinfo.io/" . $model->registration_ip, ["target" => "_blank"]);
                },
                'format' => 'raw',
                'visible' => User::hasPermission('viewRegistrationIp'),
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],
            [
                'value' => function(User $model) {
                    return GhostHtml::a(
                        UsersModule::t('back', 'Roles and permissions'),
                        ['/user/user-permission/set', 'id' => $model->id],
                        [
                            'class' => 'btn btn-sm btn-primary',
                            'data-pjax' => 0,
                            'data-set-roles' => $model->id
                        ]
                    );
                },
                'format' => 'raw',
                'visible' => User::canRoute('/user/permission/set'),
                'options' => [
                    'width' => '10px',
                ],
            ],
            [
                'value' => function(User $model) {
                    return GhostHtml::a(
                        UsersModule::t('back', 'Change password'),
                        ['change-password', 'id' => $model->id],
                        [
                            'class' => 'btn btn-sm btn-default',
                            'data-pjax' => 0,
                            'data-change-password' => $model->id
                        ]
                    );
                },
                'format' => 'raw',
                'options' => [
                    'width' => '10px',
                ],
            ],
            [
                'class' => 'roman444uk\yii\grid\StatusColumn',
                'attribute' => 'status',
                'optionsArray' => [
                    [User::STATUS_ACTIVE, UsersModule::t('back', 'Active'), 'success'],
                    [User::STATUS_INACTIVE, UsersModule::t('back', 'Inactive'), 'warning'],
                    [User::STATUS_BANNED, UsersModule::t('back', 'Banned'), 'danger'],
                ],
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'class' => 'actions',
                 ],
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'title' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                            'data-view' => $key
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                            'data-update' => $key
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>

    <?= MagnificPopup::widget([
        'id' => '',
        'type' => 'ajax',
        'target' => '#user-grid-create-button, a[data-update], a[data-view]',
        'options' => [
            'removalDelay' => 300
        ],
    ]) ?>

<?php Pjax::end() ?>

<?php $js = <<<JS
jQuery(document).on('ajaxSubmitSuccess', '#user-form', function(data) {
    alert('Изменения сохранены!');
    $('#user-grid-create-button').magnificPopup('close');
    $.pjax.reload({container: '#user-grid-pjax'});
    return false;
})
JS;
    $this->registerJs($js)
?>