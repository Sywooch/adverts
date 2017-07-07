<?php

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \app\modules\adverts\models\search\AdvertSearch */

use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\widgets\ListView;
use yii\helpers\Html;

?>

<?php Pjax::begin(['id' => 'advert-list-pjax']) ?>

<?= ListView::widget([
    'id' => 'adverts-list',
    'dataProvider' => $dataProvider,
    'itemView' => '@app/modules/adverts/views/front/advert/_advert',
    'showOnEmpty' => true,
    'layout' => "{summary}" . /*WidgetPageSize::widget([
            'pjaxId' => 'advert-list-pjax',
            'viewFile' => '@frontend/widgets/views/widget-page-size',
            'independentChanging' => true,
            'enableClearFilters' => true,
            'filterSelectors' => '#detaile-search-form input[type="text"], #detaile-search-form select',
            'clearFiltersButtonOptions' => [
                'tag' => 'span',
                'class' => 'button',
            ],
            'dropDownOptions' => [
                'items' => [
                    5 => 5,
                    10 => 10,
                    20 => 20,
                    30 => 30,
                    50 => 50,
                    100 => 100,
                ]
            ],
            'containerOptions' => [
                'class' => 'widget-page-size'
            ],
            'text' => Yii::t('app', 'Count records')
        ])*/ "
            <div class='clear'></div>
            {pager}
            {items}
            <div class='clear'></div>
            {pager}
        ",
    'options' => [
        'class' => 'adverts-list'
    ],
    'itemOptions' => [
        'class' => 'container'
    ]
]) ?>

<?php Pjax::end() ?>