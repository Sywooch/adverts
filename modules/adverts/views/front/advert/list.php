<?php

use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\helpers\Html;
use roman444uk\yii\widgets\WidgetPageSize;
use roman444uk\bookmarks\BookmarksModule;

?>

<?php Pjax::begin(['id' => 'advert-list-pjax']) ?>

    <?php echo \frontend\widgets\AdvertList::widget([
        'id' => 'advert-list',
        'dataProvider' => $dataProvider,
        'itemView' => '@frontend/views/advert/_advert',
        'showOnEmpty' => true,
        'layout' => "{summary}" . WidgetPageSize::widget([
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
        ]) . "
            <div class='clear'></div>
            {pager}
            {items}
            <div class='clear'></div>
            {pager}
        ",
        'itemOptions' => [
            'class' => 'advert-container'
        ]
    ]) ?>
    
<?php Pjax::end() ?>

<?php \roman444uk\likes\widgets\Likes::widget() ?>

<?php (new \roman444uk\bookmarks\widgets\Bookmarks([
    'clientEvents' => [
    'onAdd' => "function(event, data) {
    $(this).addClass('bookmarked').attr('title', '" . BookmarksModule::t('Delete from bookmarks') . "');
}",
    'onRemove' => "function(event, data) {
    $(this).removeClass('bookmarked').attr('title', '" . BookmarksModule::t('Add to bookmarks') . "')
}",
    'onSuccess' => "function(event, data) {
    jQuery('#bookmarks-count').html(data.count);
}",
    'onError' => "function(event, jqXHR, link) {
    alert('Error adding bookmark');
}"
    ]
]))->registerClientScript() ?>

<?php /*\roman444uk\magnificPopup\MagnificPopup::widget([
    'id' => '',
    'type' => 'ajax',
    'target' => '[data-add-bookmark]',
    'options' => [
        'prependTo' => '.content',
        'removalDelay' => 300,
        'showCloseBtn' => true,
        'closeMarkup' => '<a title="%title%" class="icon delete mfp-close"></a>'
    ]
])*/ ?>
<div class="clear"></div>