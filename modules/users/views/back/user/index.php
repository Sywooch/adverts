<?php

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\users\models\search\UserSearch $searchModel
 */

use app\modules\core\widgets\Modal;
use app\modules\users\models\ar\User;
use app\modules\users\UsersModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

$this->title = UsersModule::t('Список пользователей');

?>
        
<?php Pjax::begin(['id' => 'user-grid-pjax']) ?>

    <?= GridView::widget([
        'id' => 'user-grid',
        'dataProvider' => $dataProvider,
        //'itemsOrderDesc' => true,
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
                </div>
                <div class="col-sm-4 text-center">
                    {summary}
                </div>
                <div class="col-sm-4 text-right">
                </div>
            </div>
            {items}
            <div class="row">
                <div class="col-sm-8">
                    {pager}
                </div>
                <div class="col-sm-4 text-right" style="padding: 20px">
                    ' . /*GridBulkActions::widget(['gridId' => 'user-grid'])*/ '
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
                'attribute' => 'fullName',
                'value' => function(User $model) {
                    return Html::a(
                        $model->profile->fullName,
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
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],
            /*[
                'class' => 'roman444uk\yii\grid\StatusColumn',
                'attribute' => 'email_confirmed',
                'visible' => User::hasPermission('viewUserEmail'),
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],*/
            /*[
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
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update}',
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
                    },
                ]
            ],
        ],
    ]); ?>

    <?php /*MagnificPopup::widget([
        'id' => '',
        'type' => 'ajax',
        'target' => '#user-grid-create-button, a[data-update], a[data-view]',
        'options' => [
            'removalDelay' => 300
        ],
    ])*/ ?>

<?php Pjax::end() ?>

<?= Modal::widget([
    'id' => 'user-grid-modal',
    'size' => Modal::SIZE_LARGE,
    'openButtonSelector' => '[data-view],[data-update]',
]); ?>

<?php $js = <<<JS
jQuery(document).on('ajaxSubmitSuccess', '#user-form', function(data) {
    alert('Изменения сохранены!');
    $('#user-grid-create-button').magnificPopup('close');
    $.pjax.reload({container: '#user-grid-pjax'});
    return false;
})
JS;
?>