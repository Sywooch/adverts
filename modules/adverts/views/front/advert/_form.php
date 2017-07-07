<?php

//use common\helpers\DatepickerHelper;
//use common\helpers\MaskedInputHelper;
//use roman444uk\jqueryUpoadFilePlugin\JQueryUpoadFilePlugin;
//use roman444uk\yii\widgets\ActiveForm;
use app\modules\adverts\AdvertsModule;
use app\modules\adverts\models\search\AdvertCategorySearch;
use app\modules\core\widgets\ActiveForm;
use app\modules\currencies\models\search\CurrencySearch;
use nkovacs\datetimepicker\DateTimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

?>

<!-- Advert form -->
<?php $form = ActiveForm::begin([
    'id' => 'advert-form',
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validateOnBlur' => true,
    'validationUrl' => Url::to(['validate', 'id' => $model->id]),
    'fieldConfig' => [
        'template' => "{label}\n{input}",
    ]
]) ?>

    <div class="row">
        <?= $form->field($model, 'content', [
            'options' => [
                'class' => 'col-sm-12 col-md-12 col-lg-12'
            ]
        ])->textarea([
            'rows' => '10',
        ]) ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($model, 'category_id', [
            'options' => [
                'class' => 'col-sm-4 col-md-4 col-lg-4'
            ]
        ])->dropDownList(ArrayHelper::map(AdvertCategorySearch::getList(), 'id', 'name')); ?>

        <?php $model->expiry_at = Yii::$app->formatter->asDate($model->expiry_at, Yii::$app->formatter->dateFormat); ?>
        <?= $form->field($model, 'expiry_at', [
            'options' => [
                'class' => 'col-sm-4 col-md-4 col-lg-4'
            ]
        ])->widget(DateTimePicker::className(), [
            'class' => 'form-control',
            'format' => Yii::$app->formatter->dateFormat,
            'locale' => Yii::$app->language,
        ]); ?>
    </div>

    <div class="row mt-10">
        <?= $form->field($model, 'currency_id', [
            'options' => [
                'class' => 'col-sm-4 col-md-4 col-lg-4'
            ]
        ])->dropDownList(ArrayHelper::map(CurrencySearch::getList(), 'id', 'name')); ?>


        <?= $form->field($model, 'min_price', [
            'options' => [
                'class' => 'col-sm-4 col-md-4 col-lg-4'
            ]
        ])->textInput(); ?>

        <?= $form->field($model, 'max_price', [
            'options' => [
                'class' => 'col-sm-4 col-md-4 col-lg-4'
            ]
        ])->textInput(); ?>
    </div>

    <?php /*$form->field($model, 'city_id')->dropDownList(City::getList(), [
        'name' => ($directPopulating) ? 'city_id' : null,
        'label' => 'City',
        'emptyItem' => Yii::t('app', 'Empty city option'),
    ])*/ ?>

    <div class="btn-group mt-20">
        <?php if ($model->isNewRecord): ?>
            <?= Html::submitButton(AdvertsModule::t('Опубликовать'), [
                'class' => 'btn btn-success'
            ]); ?>

            <?php
            // TODO: взвесить и реализовать возможность добавления заметок в черновики
            /*Html::a(AdvertsModule::t('Сохранить как черновик'), Url::to(['/advert/clear-templet']), [
                'class' => 'btn btn-success'
            ]);*/
            ?>

            <?= Html::a(AdvertsModule::t('Очистить'), Url::to(['/advert/clear-templet']), [
                'class' => 'btn btn-warning'
            ]) ?>
        <?php else: ?>
            <?= Html::submitButton(AdvertsModule::t('Сохранить изменения')); ?>
        <?php endif; ?>
    </div>

    <div class="clear"></div>

<?php ActiveForm::end() ?>

<!-- Advert upload files form -->
<?php $form = ActiveForm::begin([
    'id' => 'advert-file-upload-form',
    'action' => '/file/upload',
    'options' => [
        'enctype' => 'multipart/form-data'
    ],
    'enableClientValidation' => false,
    'fieldConfig' => [
        'template' => "{input}"
    ]
]) ?>

    <?= Html::hiddenInput('owner', 'Advert') ?>
    <?= Html::hiddenInput('ownerId', $model->id) ?>

    <label>
        <?php /*$form->field(new File, 'file', [
            'options' => ['tag' => false]
        ])->fileInput();*/ ?>

        <span class="button" style="">
            <?= Yii::t('app', 'Attach file') ?>
        </span>
    </label>

    <?= Html::submitButton(Yii::t('app', 'Upload')) ?>

<?php ActiveForm::end() ?>

<?php //$this->render('dropzone/register') ?>

<div id="advert-uploaded-files">
    <?php //foreach ($model->files as $file): ?>
        <?php //$this->render('dropzone/_file', ['model' => $file]) ?>
    <?php //endforeach ?>
</div>

<?php
    /*MaskedInput::widget([
       'id' => 'term_at', 
       'name' => 'term_at',
       'mask' => 'd m y',
       'definitions' => MaskedInputHelper::getDatepickerDefinitions()
    ]);*/
?>

<?php
    $saveTempletUrl = Url::to('/adverts/advert/save-templet');
    $js = <<<JS
jQuery('#advert-form').on('ajaxComplete', function(data) {
    $.ajax({
        url: '{$saveTempletUrl}',
        method: 'post',
        data: $(this).serialize(),
        success: function(data, textStatus, jqXHR ) {
            
        },
        error: function() {
            alert('error. Посмотри firebug!');
        }
    });    
});

jQuery('#advert-uploaded-files').on('click', '[data-delete-file]', function() {
    var a = $(this);
    $.ajax({
        url: $(this).attr('href'),
        success: function(data, textStatus, jqXHR ) {
            a.parents('.dz-preview').animate({
                opacity: 0
            }, 300, function() {
                $(this).remove();
            });
        },
        error: function() {
            alert('error. Посмотри firebug!');
        }
    });
    return false;
})
JS;
    $this->registerJs($js, View::POS_READY);
?>