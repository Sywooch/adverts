<?php

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\adverts\models\search\AdvertSearch $searchModel
 */

use app\modules\adverts\AdvertsModule;
use app\modules\adverts\models\ar\Advert;
use app\modules\core\widgets\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = AdvertsModule::t('Список объявлений');

?>

<?php Pjax::begin(['id' => 'advert-grid-pjax']) ?>

    <?= GridView::widget([
        'id' => 'advert-grid',
        'dataProvider' => $dataProvider,
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
                'attribute' => 'created_at',
                'format' => 'raw',
            ],
            [
                'attribute' => 'user_id',
                'value' => function(Advert $model) {
                    return Html::a($model->user->profile->fullName, $model->user->profile->url, [
                        'data-action' => 'user-view',
                        'data-pjax' => 0
                    ]);
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'category_id',
                'value' => function(Advert $model) {
                    return $model->category->name;
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'geography_id',
                'value' => function(Advert $model) {
                    return $model->geography->title;
                },
                'format' => 'raw',
            ],
            [
                'class' => 'app\modules\core\grid\StatusColumn',
                'attribute' => 'status',
                'toggleUrl' => Url::to(['/adverts/advert/update', 'id'=>'_id_']),
                'optionsArray' => [
                    [Advert::STATUS_NEW, $searchModel->getAttributeLabels('status', Advert::STATUS_NEW), 'success'],
                    [Advert::STATUS_ACTIVE, $searchModel->getAttributeLabels('status', Advert::STATUS_ACTIVE), 'info'],
                    [Advert::STATUS_BLOCKED, $searchModel->getAttributeLabels('status', Advert::STATUS_BLOCKED), 'warning'],
                ],
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ]
            ],
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
                            'data-action' => 'advert-view',
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                            'title' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                            'data-action' => 'advert-update',
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>

<?php Pjax::end() ?>

<?= Modal::widget([
    'id' => 'advert-grid-modal',
    'size' => Modal::SIZE_LARGE,
    'openButtonSelector' => '[data-action=advert-view],[data-action=advert-update]',
]); ?>

<?= Modal::widget([
    'id' => 'user-view-modal',
    'size' => Modal::SIZE_LARGE,
    'openButtonSelector' => '[data-action=user-view]',
]); ?>


<?php
    $js = <<<JS
jQuery(document).on('ajaxSubmitComplete', '#advert-form', function(event, jqXHR) {
    var url = jqXHR.getResponseHeader('X-Reload-Url');
    if (url) { 
        $('#advert-grid-modal').find('.modal-body').load(url, [], function() {
            $('#advert-grid-modal').scrollTop(0);
        });
    }
});
JS;
    $this->registerJs($js);



$status = Advert::STATUS_ACTIVE;
$js = <<<JS
jQuery(document).on('click', '[data-action=advert-publish]', function(e) {
    var self = $(this);
    var data = {
        'status': '{$status}'
    };
    $.ajax({
        url: self.attr('data-url'), 
        method: 'POST',
        dataType: 'json',
        data: data,
        success: function(data, textStatus, jqXHR) {
            if (data.success) {
                $.pjax.reload({container: '#advert-grid-pjax'});
                $('#advert-grid-modal').modal('hide').find('.modal-body').html('');
            }            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error! See firebug..');          
        }
    });
    e.preventDefault();
});
JS;
$this->registerJs($js);