<?php

//use common\helpers\DatepickerHelper;
//use common\helpers\MaskedInputHelper;
//use roman444uk\jqueryUpoadFilePlugin\JQueryUpoadFilePlugin;
//use roman444uk\yii\widgets\ActiveForm;
use app\modules\adverts\AdvertsModule;
use app\modules\adverts\models\search\AdvertCategorySearch;
use app\modules\core\widgets\ActiveForm;
use app\modules\core\widgets\FileUpload;
use app\modules\currencies\models\search\CurrencySearch;
use nkovacs\datetimepicker\DateTimePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var \app\modules\adverts\models\ar\Advert $model
 * @var \app\modules\adverts\models\ar\AdvertTemplet $templet
 * @var \yii\web\View $this
 */

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

    <div class="row mt-30">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="files-container" data-action="files-container">
                <?php /** @var $file \app\modules\core\models\ar\File */ ?>
                <?php foreach ($templet->files as $file): ?>
                    <div class="file-container" data-action="file-container">
                        <?= Html::img("/uploaded/{$file->file_name}", [
                            'class' => 'img-thumbnail'
                        ]); ?>
                        <div class="file-delete" data-action="file-delete" data-url="<?= Url::to(['file-delete', 'name' => $file->file_name]); ?>">
                            <i class="glyphicon glyphicon-remove"></i>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div id="files-progressbar" class="files-progressbar progress"></div>
        </div>
    </div>

    <div class="clear"></div>

    <?php
        $urlParam = Yii::$app->security->generateRandomString(8);
        $deleteUrlParam = Yii::$app->security->generateRandomString(8);
        $file = Html::img($urlParam, [
            'class' => 'img-thumbnail'
        ]);
        $imgTemplate = <<<TMPL
<div class="file-container" data-action="file-container">{$file}<div class="file-delete" data-action="file-delete" data-url="{$deleteUrlParam}"><i class="glyphicon glyphicon-remove"></i></div></div>
TMPL;

    ?>

    <div class="mt-20">
        <?= FileUpload::widget([
            'model' => $templet,
            'attribute' => 'files',
            'plus' => true,
            'url' => [
                'file-upload',
                'id' => $templet->id
            ],
            'clientOptions' => [
                'accept' => 'image/*',
                //'acceptFileTypes' => '/(\.|\/)(gif|jpe?g|png|bmp|pdf|doc|docx|xls|xlsx)$/i',
                'dataType' => 'json',
                'getFilesFromResponse' => true,
                'maxFileSize' => 2000000,
                'multiple' => 'multiple',
            ],
            // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
            'clientEvents' => [
                'fileuploadadd' => "function(e, data) {
                    
                }",
                'fileuploadprogressall' => "function(e, data) {
                    alert('fileuploadprogressall');
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#files-progressbar .progress-bar').css('width', progress + '%');
                }",
                'fileuploaddone' => "function(e, data) {
                    var template = '{$imgTemplate}';
                    var file = jQuery.parseJSON(data.result);
                    template = template.replace(/{$urlParam}/g, file.url);
                    template = template.replace(/{$deleteUrlParam}/g, file.deleteUrl);
                    $('[data-action=files-container]').append(template);
                    /*$('.files-container .file-container').last().find('img').animate({
                        height: '100%'
                    }, 300, function() {});*/
                }",
                'fileuploadfail' => 'function(e, data) {
                    console.log(e);
                    console.log(data);
                }',
            ],
        ]); ?>

        <div class="btn-group pull-right">
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

                <?= Html::a(AdvertsModule::t('Очистить'), Url::to(['clear-templet']), [
                    'class' => 'btn btn-warning'
                ]) ?>
            <?php else: ?>
                <?= Html::submitButton(AdvertsModule::t('Сохранить изменения')); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="clear"></div>

<?php ActiveForm::end(); ?>

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

jQuery('#advert-form').on('click', '[data-action=file-delete]', function() {
    var self = $(this);
    $.ajax({
        url: self.attr('data-url'),
        success: function(data, textStatus, jqXHR ) {
            self.prev().animate({
                width: 0
            }, 300, function() {
                self.parents('.file-container').remove();
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