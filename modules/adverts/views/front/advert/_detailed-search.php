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
            ]); ?>
            
            <?= $form->field($searchModel, 'type')->dropDownList(Advert::getTypesDropDownList(), [
                'name' => ($directPopulating) ? 'type' : null,
                'emptyItem' => Yii::t('app', 'Empty type option'),
            ]) ?>
            
            <div class="button-container">
                <?= Html::submitInput('Искать', [
                    'class' => 'clear'
                ]) ?>

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