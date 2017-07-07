<?php

use common\helpers\DatepickerHelper;
use common\helpers\MaskedInputHelper;
use common\models\Advert;
use common\models\Category;
use common\models\City;
use common\models\Currency;

use roman444uk\yii\widgets\ActiveForm;
use roman444uk\yii\widgets\WidgetPageSize;

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;
use yii\widgets\Spaceless;

$searchModel = $this->params['searchModel'];
$directPopulating = $this->params['directPopulating'];

?>

<div id="detaile-search-panel" class="detaile-search-panel">
    <h3>Критерии поиска:</h3>
    <?php if (($safeAttributes = $searchModel->safeAttributes())): ?>
        <?php $form = ActiveForm::begin([
            'id' => 'detaile-search-form',
            'method' => 'get',
            'action' => '/',
            'fieldConfig' => [
                'template' => '{label}{input}'
            ]
        ]) ?>

            <?=Yii::t('app', 'Show only active') ?>
            
            <?= $form->field($searchModel, 'city_id')->dropDownList(City::getList(), [
                'name' => ($directPopulating) ? 'city_id' : null,
                'emptyItem' => Yii::t('app', 'Empty city option'),
            ]) ?>
            
            <?= $form->field($searchModel, 'type')->dropDownList(Advert::getTypesDropDownList(), [
                'name' => ($directPopulating) ? 'type' : null,
                'emptyItem' => Yii::t('app', 'Empty type option'),
            ]) ?>
            
            <?= $form->field($searchModel, 'category')->dropDownList(Category::getDropdownList(), [
                'name' => ($directPopulating) ? 'category' : null,
                'emptyItem' => Yii::t('app', 'Empty category option'),
            ]) ?>

            <div class="range">
                <label class="control-label" for="min_date"><?=Yii::t('app', 'Period') ?></label>
                <br>
                <?php Spaceless::begin(); ?>
                    <?= $form->field($searchModel, 'min_date', [
                        'template' => "{input}",
                    ])->datepicker([
                        'name' => ($directPopulating) ? 'min_date' : null,
                        'placeholder' => $searchModel->getAttributeLabel('min_date'),
                        'datepicker' => [
                            'dateFormat' => Yii::$app->formatter->dateFormat,
                            'clientOptions' => [
                                'maxDate' => "0",
                                'monthNamesShort' => DatepickerHelper::monthNamesShort(),
                            ]
                        ]
                    ]) ?>

                    <?= $form->field($searchModel, 'max_date', [
                        'template' => "{input}",
                    ])->datepicker([
                        'name' => ($directPopulating) ? 'max_date' : null,
                        'class' => 'without-left-border',
                        'placeholder' => $searchModel->getAttributeLabel('max_date'),
                        'datepicker' => [
                            'dateFormat' => Yii::$app->formatter->dateFormat,
                            'clientOptions' => [
                                'maxDate' => "0",
                                'monthNamesShort' => DatepickerHelper::monthNamesShort()
                            ]
                        ]
                    ]) ?>
                <?php Spaceless::end(); ?>
                <div class="clear"></div>
            </div>
    
            <div class="range">
                <label class="control-label" for="min_price"><?= Yii::t('app', 'Price') ?></label>
                <br>
                <?php Spaceless::begin(); ?>
                    <?= $form->field($searchModel, 'min_price', [
                        'template' => "{input}",
                    ])->textInput([
                        'name' => ($directPopulating) ? 'min_price' : null,
                        'placeholder' => $searchModel->getAttributeLabel('min_price'),
                    ]) ?>

                    <?= $form->field($searchModel, 'max_price', [
                        'template' => "{input}",
                    ])->textInput([
                        'name' => ($directPopulating) ? 'max_price' : null,
                        'class' => 'without-left-border',
                        'placeholder' => $searchModel->getAttributeLabel('max_price'),
                    ]) ?>

                    <?= $form->field($searchModel, 'currency', [
                        'template' => "{input}{error}",
                        'options' => ['tag' => false],
                    ])->dropDownList(Currency::getDropDownList(), [
                        'name' => ($directPopulating) ? 'currency' : null,
                        'emptyItem' => Yii::t('app', 'Currency'),
                    ]) ?>
                <?php Spaceless::end(); ?>
                <div class="clear"></div>
            </div>
            
            <div class="button-container">
                <?= Html::submitInput('Искать', [
                    'class' => 'clear'
                ]) ?>
                    
                <?php
                    $js = <<<JS
$('#detaile-search-panel .button-container input').hide();
jQuery('#detaile-search-form').on('change.yiiActiveForm', function(event) {
    $.pjax.submit(event, '#advert-list-pjax');
});
jQuery(document).on('pjax:beforeSend', function(data, xhr, options) {
    var targetId = options.target ? options.target.id : null;
    if (targetId == 'detaile-search-form' || targetId == 'search-form') {
        var params = [];;
        var func = function(i, field) {
            if (field.value) {
                params.push(field.name + '=' + field.value);
            }
        }
        $.each(jQuery('#search-form').serializeArray(), func);
        $.each(jQuery('#detaile-search-form').serializeArray(), func);
        options.url = options.url.split('?')[0] + '?' + params.join('&');
    }
})
JS;
                    
                    $this->registerJs($js);
                ?>
            </div>

        <?php ActiveForm::end() ?>
    
        <?php
            /*MaskedInput::widget([
                'id' => 'min_date', 
                'name' => 'min_date',
                'mask' => 'd m y',
                'definitions' => MaskedInputHelper::getDatepickerDefinitions()
            ]);
    
            MaskedInput::widget([
                'id' => 'dateto', 
                'name' => 'dateTo',
                'mask' => 'd m y',
                'definitions' => MaskedInputHelper::getDatepickerDefinitions()
            ]);*/
            
            /*MaskedInput::widget([
                'id' => 'minprice', 
                'name' => 'minPrice',
                'mask' => 'c',
                'definitions' => [
                    'c' => [
                        'validator' => '[0-9]{1,3}',
                    ]
                ]
            ]);*/
        ?>
    <?php endif ?>
</div>