<?php

use app\modules\adverts\models\ar\AdvertTemplet;
use app\modules\adverts\models\search\AdvertSearch;
use app\modules\adverts\models\search\AdvertCategorySearch;
use app\modules\core\widgets\ActiveForm;
use app\modules\core\widgets\inputs\dateTimePicker\DateTimePicker;
use app\modules\core\widgets\inputs\multiSelect\MultiselectPopup;
use app\modules\currency\models\search\CurrencySearch;
use app\modules\geography\models\ar\Geography;
use app\modules\geography\models\search\GeographySearch;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var AdvertSearch $model
 * @var AdvertTemplet $templet
 * @var \yii\web\View $this
 */

$expandIcons = '<span class="collapsed pull-right"></span><span class="expanded pull-right"></span>';

?>

<div class="adverts-list-filter">
    <?php $form = ActiveForm::begin([
        'id' => 'filter-form',
        'options' => [
            'csrf' => false,
        ],
        'fieldConfig' => [
            'template' => '{input}',
            'inputOptions' => [
                'class' => 'form-control input-sm'
            ]
        ]
    ]); ?>

        <!-- phrase -->
        <?= Html::tag('label', $model->getAttributeLabel('phrase') . $expandIcons, [
            'class' => 'collapsed',
            'data-toggle' => 'collapse',
            'href' => '#collapse-phrase',
            'aria-expanded' => false,
            'aria-controls' => 'collapse-phrase',
        ]); ?>

        <div id="collapse-phrase" class="collapse<?= $model->phrase ? ' in' : ''; ?>">
            <?= $form->field($model, 'phrase', [
                'options' => [
                    'class' => 'form-group mb-0',
                ],
                'template' => '<div class="input-group">{input}<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>'
            ])->textInput(); ?>
        </div>

        <!-- geography_id -->
        <?= Html::tag('label', $model->getAttributeLabel('geography_id') . $expandIcons, [
            'class' => 'collapsed',
            'data-toggle' => 'collapse',
            'href' => '#collapse-geography_id',
            'aria-expanded' => true,
            'aria-controls' => 'collapse-geography_id',
        ]); ?>

        <div id="collapse-geography_id" class="collapse<?= $model->geography_id ? ' in' : ''; ?>">
            <?= $form->field($model, 'geography_id', [
                'template' => '{input}',
            ])->widget(MultiselectPopup::className(), [
                'model' => $model,
                'addonGlyphiconClass' => 'glyphicon-list',
                'attribute' => 'geography_id',
                'emptyText' => 'Указать',
                'notEmptyText' => 'Изменить',
                'likeInput' => true,
                'clientOptions' => [
                    'title' => 'Выбор месторасположения',
                    'dataUrl' => Url::to(['/geography/geography/index']),
                    'itemsChildEagerDisplaying' => true,
                    /*'items' => GeographySearch::getList([
                        'select' => ['id', 'title', 'items' => new \yii\db\Expression('1')],
                        'type' => Geography::TYPE_REGION,
                    ]),*/
                    'items' => GeographySearch::getCityListGroupedByRegion(),
                    'selectedValues' => $model->geography_id ? ArrayHelper::map(
                        GeographySearch::getList(['in', 'service_id', $model->geography_id], ['select' => ['service_id', 'title']]), 'service_id', 'title'
                    ) : [],
                    'selectedValuesContainerSelector' => '',
                    'showSelectedItems' => true,
                    'showSelectedInputs' => false,
                    'likeInput'
                ],
                'options' => [

                ]
            ]); ?>
        </div>

        <!-- category_id -->
        <?= Html::tag('label', $model->getAttributeLabel('category_id') . $expandIcons, [
            'class' => 'collapsed',
            'data-toggle' => 'collapse',
            'href' => '#collapse-category_id',
            'aria-expanded' => false,
            'aria-controls' => 'collapse-category_id',
        ]); ?>

        <div id="collapse-category_id" class="collapse<?= $model->category_id ? ' in' : ''; ?>">
            <?= $form->field($model, 'category_id', [
                'template' => '{input}',
            ])->widget(MultiselectPopup::className(), [
                'model' => $model,
                'attribute' => 'category_id',
                'emptyText' => 'Указать',
                'notEmptyText' => 'Изменить',
                'clientOptions' => [
                    'title' => 'Выбор категории',
                    'itemsDisplayMode' => MultiselectPopup::ITEMS_DISPLAY_MODE_INLINE,
                    'items' => ArrayHelper::map(
                        AdvertCategorySearch::getList([], ['select' => ['id', 'name']]), 'id', 'name'
                    ),
                    'selectedValues' => !empty($model->category_id) ? ArrayHelper::map(
                        AdvertCategorySearch::getList(['in', 'id', $model->category_id], ['select' => ['id', 'name']]), 'id', 'name'
                    ) : [],
                    'showSelectedItems' => true,
                    'showSelectedInputs' => false,
                    'showNavigation' => false,
                ],
                'options' => [
                    'tag' => 'span',
                    'class' => 'pl-5 cursor-pointer',
                ]
            ]); ?>
        </div>

        <!-- min_date, max_date -->
        <?= Html::tag('label', Yii::t('app', 'Дата публикации') . $expandIcons, [
            'class' => 'collapsed',
            'data-toggle' => 'collapse',
            'href' => '#collapse-date',
            'aria-expanded' => false,
            'aria-controls' => 'collapse-date',
        ]); ?>

        <div id="collapse-date" class="collapse<?= $model->min_date || $model->max_date ? ' in' : ''; ?>">
            <div class="row">
                <?= $form->field($model, 'min_date', [
                    'options' => [
                        'class' => 'form-group col-xs-12 col-sm-12 col-md-12 col-lg-12',
                    ],
                    'template' => "{input}",
                ])->widget(DateTimePicker::className(), [
                    'options' => [
                        'class' =>'form-control input-sm',
                        'placeholder' => Yii::t('app', 'от'),
                        'value' => $model->getFormattedDatetime('min_date'),
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                    ],
                ]); ?>

                <?= $form->field($model, 'max_date', [
                    'options' => [
                        'class' => 'form-group col-xs-12 col-sm-12 col-md-12 col-lg-12 mb-0',
                    ],
                    'template' => "{input}",
                ])->widget(DateTimePicker::className(), [
                    'options' => [
                        'class' =>'form-control input-sm',
                        'placeholder' => Yii::t('app', 'до'),
                        'value' => $model->getFormattedDatetime('max_date'),
                    ],
                    'pluginOptions' => [
                        'autoclose' => true,
                    ],
                ]); ?>
            </div>
        </div>

        <!-- min_price, max_price -->
        <?= Html::tag('label', Yii::t('app', 'Цена') . $expandIcons, [
            'class' => 'collapsed',
            'for' => 'min_price',
            'data-toggle' => 'collapse',
            'href' => '#collapse-price',
            'aria-expanded' => false,
            'aria-controls' => 'collapse-price',
        ]); ?>

        <div id="collapse-price" class="collapse<?= $model->min_price|| $model->max_price ? ' in' : ''; ?>">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?= $form->field($model, 'min_price', [
                        'options' => [
                            'class' => 'form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 pl-0',
                            'style' => 'padding-right: 0'
                        ],
                        'template' => "{input}",
                    ])->textInput([
                        'placeholder' => Yii::t('app', 'от')
                    ]); ?>

                    <?= $form->field($model, 'max_price', [
                        'options' => [
                            'class' => 'form-group col-xs-12 col-sm-12 col-md-6 col-lg-6 pr-0',
                            'style' => 'padding-left: 0'
                        ],
                        'template' => "{input}",
                    ])->textInput([
                        'placeholder' => Yii::t('app', 'до')
                    ]); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-sm-offset-2 col-md-offset-2 col-lg-offset-2">
                    <?= $form->field($model, 'currency_id', [
                        'template' => "{input}",
                    ])->dropDownList(ArrayHelper::map(CurrencySearch::getList(), 'id', 'short_name')); ?>
                </div>
            </div>
        </div>

        <?php Html::a('Сбросить фильтр', Url::home(), [
            'class' => 'btn btn-secondary btn-sm col-12'
        ]); ?>

    <?php ActiveForm::end(); ?>


    <?php
        $js = <<<JS
    jQuery('#filter-form').on('change.yiiActiveForm', function(event) {
        $.pjax.submit(event, '#adverts-list-pjax');
    });
    jQuery(document).on('pjax:beforeSend', function(data, xhr, options) {
        var targetId = options.target ? options.target.id : null;
        if (targetId == 'filter-form' || targetId == 'search-form') {
            var params = {}, url = [];
            $.each(window.location.search.slice(window.location.search.indexOf('?') + 1).split('&'), function(i, field) {
                var parts = field.split('=');
                if (parts.length == 2) {
                    params[parts[0]] = parts[1];
                }
            });
            $.each(jQuery('#filter-form').serializeArray(), function(i, field) {
                if (field.value) {
                    params[field.name] = field.value;
                }
            });
            $.each(params, function(name, value) {
                url.push(name + '=' + value);
            }); 
            options.url = options.url.split('?')[0] + '?' + url.join('&');
        }
    });
JS;
        $this->registerJs($js);
    ?>
</div>