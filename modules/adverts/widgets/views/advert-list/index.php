<?php

use app\modules\adverts\widgets\AdvertList;
use app\modules\adverts\models\search\AdvertSearch;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var AdvertList $widget
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var AdvertSearch $searchModel
 * @var bool $renderFilter
 * @var string $tag
 */

?>

<?= Html::beginTag($tag, $widget->options); ?>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 text-center-xs text-center-sm text-center-md text-left-lg">
            {sorter}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 text-center-xs text-center-sm text-center-md text-right-lg">
            {widgetPageSize}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
            {summary}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
            {pager}
        </div>
    </div>
    <div class="row">
        {items}
    </div>
    <div class='clear'></div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
        {pager}
    </div>
<?= Html::endTag($tag); ?>