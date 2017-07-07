<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;

use webvimark\extensions\DateRangePicker\DateRangePicker;
use roman444uk\yii\widgets\WidgetPageSize;

use app\modules\users\UsersModule;

$this->title = UsersModule::t('back', 'Visit log');
$this->params['breadcrumbs'][] = $this->title;

?>


<!--<?= WidgetPageSize::widget([
    'pjaxId' => 'user-visit-log-grid-pjax'
]) ?>-->

<?php Pjax::begin(['id' => 'user-visit-log-grid-pjax']) ?>

    <?= GridView::widget([
        'id' => 'user-visit-log-grid',
        'dataProvider' => $dataProvider,
        'pager' => [
            'options' => ['class'=>'pagination pagination-sm'],
            'hideOnSinglePage' => true,
            'lastPageLabel' => '>>',
            'firstPageLabel' => '<<',
        ],
        'layout' => '
            <div class="row">
                <div class="col-sm-4"></div>
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
            </div>
        ',
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'options' => [
                    'style' => 'width:10px'
                ]
            ],
            [
                'attribute' => 'user_id',
                'value' => function($model){
                    return Html::a(@$model->user->username, [
                        'view',
                        'id' => $model->id],
                        ['data-pjax' => 0]
                    );
                },
                'format' => 'raw',
            ],
            'language',
            'os',
            'browser',
            array(
                'attribute' => 'ip',
                'value' => function($model){
                    return Html::a(
                        $model->ip,
                        "http://ipinfo.io/" . $model->ip,
                        ["target"=>"_blank"]
                    );
                },
                'format' => 'raw',
            ),
            'visit_time:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'contentOptions' => [
                    'style' => 'width:70px; text-align:center;'
                ],
            ],
        ],
    ]); ?>

<?php Pjax::end() ?>

<?php DateRangePicker::widget([
    'model'     => $searchModel,
    'attribute' => 'visit_time',
]) ?>