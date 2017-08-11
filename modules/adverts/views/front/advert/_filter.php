<?php

use app\modules\adverts\models\search\AdvertCategorySearch;
use app\modules\core\models\search\CurrencySearch;
use app\modules\core\widgets\ActiveForm;
use nkovacs\datetimepicker\DateTimePicker;
use yii\web\View;
use yii\helpers\ArrayHelper;
use app\modules\geography\models\search\GeographySearch;

?>

<?php $form = ActiveForm::begin([
    'id' => 'filter-form',
    //'method' => 'get',
    //'action' => '/',
    'options' => [

    ],
    'fieldConfig' => [
        'template' => '{label}{input}',
        'inputOptions' => [
            'class' => 'form-control input-sm'
        ]
    ]
]); ?>

    <?= $form->field($model, 'phrase', [
        'template' => '{label}<div class="input-group">{input}<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>{error}'
    ])->textInput(); ?>

    <?= $form->field($model, 'geography_id')->dropDownList(GeographySearch::getCityListGropedByRegion(), [
        'emptyItem' => Yii::t('app', 'Все'),
    ]); ?>

    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(AdvertCategorySearch::getList(), 'id', 'name'), [
        'emptyItem' => Yii::t('app', 'Все'),
    ]); ?>

    <label class="control-label" for="min_date"><?= Yii::t('app', 'Дата публикации') ?></label>
    <div class="row">
        <?= $form->field($model, 'min_date', [
            'options' => [
                'class' => 'form-group col-xs-12 col-sm-12 col-md-12 col-lg-12',
            ],
            'template' => "{input}",
        ])->widget(DateTimePicker::className(), [
            'format' => Yii::$app->formatter->dateFormat,
            'locale' => Yii::$app->language,
            'options' => [
                'class' =>'form-control input-sm',
                'placeholder' => Yii::t('app', 'от'),
            ]
        ]); ?>

        <?= $form->field($model, 'max_date', [
            'options' => [
                'class' => 'form-group col-xs-12 col-sm-12 col-md-12 col-lg-12',
            ],
            'template' => "{input}",
        ])->widget(DateTimePicker::className(), [
            'format' => Yii::$app->formatter->dateFormat,
            'locale' => Yii::$app->language,
            'options' => [
                'class' =>'form-control input-sm',
                'placeholder' => Yii::t('app', 'до'),
            ]
        ]); ?>
    </div>

    <label class="control-label" for="min_price"><?= Yii::t('app', 'Цена') ?></label>
    <div class="row">
        <?= $form->field($model, 'min_price', [
            'options' => [
                'class' => 'form-group col-xs-12 col-sm-12 col-md-6 col-lg-6',
                'style' => 'padding-right: 0'
            ],
            'template' => "{input}",
        ])->textInput([
            'placeholder' => Yii::t('app', 'от')
        ]); ?>

        <?= $form->field($model, 'max_price', [
            'options' => [
                'class' => 'form-group col-xs-12 col-sm-12 col-md-6 col-lg-6',
                'style' => 'padding-left: 0'
            ],
            'template' => "{input}",
        ])->textInput([
            'placeholder' => Yii::t('app', 'до')
        ]); ?>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
            <?= $form->field($model, 'currency_id', [
                'template' => "{input}",
            ])->dropDownList(ArrayHelper::map(CurrencySearch::getList(), 'id', 'name')); ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>


<?php
$js = <<<JS
jQuery('#filter-form').on('change.yiiActiveForm', function(event) {
    $.pjax.submit(event, '#adverts-list-pjax');
});
jQuery(document).on('pjax:beforeSend', function(data, xhr, options) {
    var targetId = options.target ? options.target.id : null;
    if (targetId == 'filter-form' || targetId == 'search-form') {
        var params = [];;
        var func = function(i, field) {
            if (field.value) {
                params.push(field.name + '=' + field.value);
            }
        }
        $.each(jQuery('#filter-form').serializeArray(), func);
        options.url = options.url.split('?')[0] + '?' + params.join('&');
    }
})
JS;

$this->registerJs($js);
?>
