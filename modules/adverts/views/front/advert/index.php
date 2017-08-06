<?php

/**
 * @var $this yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 * @var $searchModel \app\modules\adverts\models\search\AdvertSearch
 */

use app\modules\adverts\widgets\AdvertListView;
use yii\widgets\Pjax;

?>

<div class="row adverts-list-container">
    <div class="col-xs-12 left">
        <?= $this->render('_filter', [
            'model' => $searchModel,
        ]); ?>
    </div>
    <?php Pjax::begin(['id' => 'adverts-list-pjax']) ?>
        <?= AdvertListView::widget([
            'id' => 'adverts-list',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'itemView' => '@app/modules/adverts/views/front/advert/_advert',
            'layout' => "  
                <div class=\"col-xs-12 right\">
                    <div class=\"row\">
                        <div class=\"col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center-xs text-center-sm\">{summary}</div>
                        <div class=\"hidden\">{pageSize}</div>
                        <div class=\"col-xs-12 col-sm-12 col-md-6 col-lg-6 text-center-xs text-center-sm text-right-lg text-right-md\">{pager}</div>
                    </div>
                    <div class=\"row\">
                        {items}
                    </div>
                </div>
                </div>
                <div class='clear'></div>
                <div class=\"col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right\">{pager}</div>
            ",
            'showOnEmpty' => true,
            'options' => [
                'class' => 'adverts-list'
            ],
            'itemOptions' => [
                'class' => 'advert-container',
            ]
        ]); ?>
    <?php Pjax::end() ?>

<?php /*WidgetPageSize::widget([
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
]);*/ ?>