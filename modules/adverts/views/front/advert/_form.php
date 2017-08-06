<?php

//use common\helpers\DatepickerHelper;
//use common\helpers\MaskedInputHelper;
//use roman444uk\jqueryUpoadFilePlugin\JQueryUpoadFilePlugin;
//use roman444uk\yii\widgets\ActiveForm;
use app\modules\adverts\AdvertsModule;
use app\modules\adverts\models\search\AdvertCategorySearch;
use app\modules\core\widgets\ActiveForm;
use app\modules\core\widgets\FileUpload;
use app\modules\core\widgets\Spaceless;
use app\modules\core\models\search\CurrencySearch;
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
    'options' => [
        'class' => 'advert-form'
    ],
    'fieldConfig' => [
        'template' => "{label}\n{input}",
        'inputOptions' => [
            'class' => 'form-control input-sm'
        ]
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
            'format' => Yii::$app->formatter->dateFormat,
            'locale' => Yii::$app->language,
            'options' => [
                'class' => 'form-control input-sm'
            ]
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
            <div class="files-list" data-action="files-list">
                <?php if ($templet->files): ?>
                    <?php /** @var $file \app\modules\core\models\ar\File */ ?>
                    <?php foreach ($templet->files as $file): ?>
                        <?= $this->render('file/_file-container', [
                            'model' => $file
                        ]); ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="files-empty"><?= Yii::t('app', 'Не загружено ни одного файла...'); ?></div>
                <?php endif; ?>
            </div>
            <?= \yii\jui\ProgressBar::widget([
                'options' => [
                    'id' => 'files-progressbar',
                    'class' => 'files-progressbar',
                ]
            ]); ?>
        </div>
    </div>

    <div class="clear"></div>

    <?php
        $urlParam = Yii::$app->security->generateRandomString(8);
        $deleteUrlParam = Yii::$app->security->generateRandomString(8);
        $imgTemplate = Spaceless::widget([
            'text' => $this->render('file/_file-container', [
                'urlParam' => $urlParam,
                'deleteUrlParam' => $deleteUrlParam
            ])
        ]);
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
    $('#files-progressbar').progressbar({
        value: parseInt(data.loaded / data.total * 100, 10)
    });
}",
                'fileuploaddone' => "function(e, data) {
    var template = '{$imgTemplate}';
    if (data.result.file) {
        var file = data.result.file;        
    }
    template = template.replace(/{$urlParam}/g, file.url);
    template = template.replace(/{$deleteUrlParam}/g, file.deleteUrl);
    $('[data-action=files-list]').append(template);
    $('#files-progressbar').progressbar({
        value: 0
    });
    $('.file-uploaded-success').css('display', 'inline').delay(4000).animate({
        opacity: 0
    }, 2000, function() {
        $('.file-uploaded-success').css('display', '')
    });
    $('.files-list .files-empty').hide();
}",
                'fileuploadfail' => "function(e, data) {
    $('.file-uploaded-success').css('display', 'inline');
}",
            ],
        ]); ?>

        <span class="file-uploaded-success">Файл загружен</span>
        <span class="file-uploaded-fail">Произошла ошибка при загрузке файла</span>

        <div class="btn-group pull-right">
            <?php if ($model->isNewRecord): ?>
                <?= Html::submitButton(AdvertsModule::t('Опубликовать'), [
                    'class' => 'btn btn-success btn-sm'
                ]); ?>

                <?php
                // TODO: взвесить и реализовать возможность добавления заметок в черновики
                /*Html::a(AdvertsModule::t('Сохранить как черновик'), Url::to(['/advert/clear-templet']), [
                    'class' => 'btn btn-success'
                ]);*/
                ?>

                <?= Html::a(AdvertsModule::t('Очистить'), Url::to(['clear-templet']), [
                    'class' => 'btn btn-warning btn-sm'
                ]) ?>
            <?php else: ?>
                <?= Html::submitButton(AdvertsModule::t('Сохранить изменения'), [
                    'class' => 'btn btn-sm'
                ]); ?>
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
    var img = self.prev();
    var container = self.parent();
    container.css('width', img.css('width')).css('height', img.css('height'));
    container.find('[data-action=file-deleting]').show();
    self.removeClass('visible');
    $.ajax({
        url: self.attr('data-url'),
        success: function(data, textStatus, jqXHR) {
            self.prev().animate({
                width: 0
            }, 300, function() {
                self.parents('.file-container').remove();
            });
            $('.files-list .files-empty').show();
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