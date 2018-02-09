<?php

use app\modules\adverts\models\search\AdvertSearch;
use app\modules\adverts\models\search\AdvertCategorySearch;
use app\modules\core\models\search\CurrencySearch;
use app\modules\core\widgets\ActiveForm;
use app\modules\core\widgets\DateTimePicker;
use app\modules\geography\models\search\GeographySearch;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var AdvertSearch $model
 * @var \app\modules\adverts\models\ar\AdvertTemplet $templet
 * @var \yii\web\View $this
 */

?>

<div class="adverts-list-filter">
    <?php $form = ActiveForm::begin([
        'id' => 'filter-form',
        'options' => [
            'csrf' => false,
        ],
        'fieldConfig' => [
            'template' => '{label}{input}',
            'inputOptions' => [
                'class' => 'form-control input-sm'
            ]
        ]
    ]); ?>

        <?= $form->field($model, 'phrase', [
            'options' => [
                'class' => 'form-group mb-0',
            ],
            'template' => '{label}<div class="input-group">{input}<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span></div>'
        ])->textInput(); ?>

        <?= $form->field($model, 'geography_id', [
            'options' => [
                'class' => 'form-group mb-0',
            ],
        ])->dropDownList(GeographySearch::getCityListGropedByRegion(), [
            'emptyItem' => Yii::t('app', 'Не выбрано'),
        ]); ?>

        <?= $form->field($model, 'category_id', [
            'options' => [
                'class' => 'form-group mb-0',
            ],
        ])->dropDownList(ArrayHelper::map(AdvertCategorySearch::getList(['select' => ['id', 'name']]), 'id', 'name'), [
            'emptyItem' => Yii::t('app', 'Не выбрано'),
        ]); ?>

        <div class="form-group mb-0">
            <label class="control-label" for="min_date"><?= Yii::t('app', 'Дата публикации') ?></label>
        </div>
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

        <div class="form-group mb-0">
            <label class="control-label" for="min_price"><?= Yii::t('app', 'Цена') ?></label>
        </div>
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
                ])->dropDownList(ArrayHelper::map(CurrencySearch::getList(), 'id', 'name')); ?>
            </div>
        </div>

        <?= Html::a('Сбросить фильтр', Url::home(), [
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
            var params = [];;
            var func = function(i, field) {
                if (field.value) {
                    params.push(field.name + '=' + field.value);
                }
            }
            $.each(jQuery('#filter-form').serializeArray(), func);
            options.url = options.url.split('?')[0] + '?' + params.join('&');
        }
    });
JS;
        $this->registerJs($js);
    ?>
</div>