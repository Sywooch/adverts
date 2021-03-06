<?php

use app\modules\adverts\AdvertsModule;
use app\modules\adverts\models\search\AdvertCategorySearch;
use app\modules\currency\models\search\CurrencySearch;
use app\modules\core\widgets\ActiveForm;
use app\modules\core\widgets\FileUpload;
use app\modules\core\widgets\Spaceless;
use app\modules\core\widgets\inputs\dateTimePicker\DateTimePicker;
use app\modules\core\widgets\inputs\multiSelect\MultiselectPopup;
use app\modules\geography\models\search\GeographySearch;

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
    'ajaxSubmit' => true, //Yii::$app->request->isAjax,
    'options' => [
        'class' => 'advert-form'
    ],
    'fieldConfig' => [
        'template' => "{label}\n{input}\n{error}",
        'inputOptions' => [
            'class' => 'form-control input-sm'
        ],
        'errorOptions' => [
            'tag' => 'div'
        ]
    ],
    'clientEvents' => [
        'ajaxSubmitComplete' => "function(event, jqXHR) {
            var url = jqXHR.getResponseHeader('X-Reload-Url');
            if (url) {
                            
            }
        }"
    ]
]); ?>

    <div class="row">
        <div class="col-sm-7 col-md-8 col-lg-9">
            <?= $form->field($model, 'content', [
                'options' => [
                    'class' => 'form-group col-sm-12 col-md-12 col-lg-12'
                ]
            ])->textarea([
                'rows' => '18',
            ]) ?>
        </div>

        <div class="col-sm-5 col-md-4 col-lg-3">
            <?= $form->field($model, 'geography_id')->widget(MultiselectPopup::className(), [
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

        <div class="col-sm-5 col-md-4 col-lg-3">
            <?= $form->field($model, 'category_id')->widget(MultiselectPopup::className(), [
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

            <?= $form->field($model, 'expiry_at', [
                'options' => [
                    'class' => 'form-group mb-0'
                ]
            ])->widget(DateTimePicker::className(), [
                'layout' => '{input}{picker}',
                'options' => [
                    'class' =>'form-control input-sm',
                    'placeholder' => Yii::t('app', 'от'),
                    'value' => $model->getFormattedDatetime('expiry_at'),
                ],
                'pluginOptions' => [
                    'autoclose' => true,
                ],
            ]); ?>

            <?= $form->field($model, 'currency_id', [
                'options' => [
                    'class' => 'form-group mb-0'
                ]
            ])->dropDownList(
                ArrayHelper::map(CurrencySearch::getList(), 'id', 'name')
            ); ?>

            <?= $form->field($model, 'min_price', [
                'options' => [
                    'class' => 'form-group mb-0'
                ]
            ])->textInput(); ?>

            <?= $form->field($model, 'max_price', [
                'options' => [
                    'class' => 'form-group mb-0'
                ]
            ])->textInput(); ?>
        </div>
    </div>

<?php /*$form->field($model, 'city_id')->dropDownList(City::getList(), [
        'name' => ($directPopulating) ? 'city_id' : null,
        'label' => 'City',
        'emptyItem' => Yii::t('app', 'Empty city option'),
    ])*/ ?>

    <div class="row mt-30">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="files-list" data-action="files-list">
                <?php
                $files = !$model->isNewRecord ? $model->files : $templet->files;
                ?>
                <?php if ($files): ?>
                    <?php /** @var $file \app\modules\core\models\ar\File */ ?>
                    <?php foreach ($files as $file): ?>
                        <?= $this->render('file/_file-container', [
                            'model' => $file
                        ]); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="files-empty<?= $files ? ' hide' : '' ?>"><?= Yii::t('app', 'Не загружено ни одного файла...'); ?></div>
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
            'model' => !$model->isNewRecord ? $model : $templet,
            'attribute' => 'files',
            'plus' => true,
            'url' => [
                'file-upload',
                'id' => !$model->isNewRecord ? $model->id : $templet->id,
                'owner' => !$model->isNewRecord ? $model::className() : $templet::className(),
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
    if (data.result.success && data.result.file) {
        var file = data.result.file;
        var template = '{$imgTemplate}';
        template = template.replace(/{$urlParam}/g, file.url);
        template = template.replace(/{$deleteUrlParam}/g, file.deleteUrl);
        $('[data-action=files-list]').append(template);
        $('.files-list .files-empty').hide();
        $('.file-uploaded-success').css('display', 'inline').delay(4000).animate({
            opacity: 0
        }, 2000, function() {
            $('.file-uploaded-success').css('display', '')
        });
        $('.file-uploaded-fail').hide();
    } else if (data.result.errors && data.result.errors.owner_id) {
        $('.file-uploaded-fail').html(data.result.errors.owner_id).css('display', 'inline');
    }
    $('#files-progressbar').progressbar({
        value: 0
    });
}",
                'fileuploadfail' => "function(e, data) {
    $('#files-progressbar').progressbar({
        value: parseInt(0, 10)
    });
    alert('Ошибка загрузки файла. Пожалуйста, попробуйте еще раз');
}",
                'fileuploadprocess' => "function(e, data) {
    $('.file-uploaded-fail').html('').hide();
                }",
                'fileuploadprocessfail' => "function(e, data) {
    var file = data.files[0];
    if (file.error) {
        $('.file-uploaded-fail').html(file.error).show();
    }
                }",
            ],
        ]); ?>

        <span class="file-uploaded-success">Файл загружен</span>
        <span class="file-uploaded-fail">Произошла ошибка при загрузке файла</span>

        <div class="btn-group pull-right">
            <?php if ($model->isNewRecord): ?>
                <?= Html::submitButton(AdvertsModule::t('Опубликовать'), [
                    'class' => 'btn btn-primary btn-sm'
                ]); ?>

                <?php
                // TODO: взвесить и реализовать возможность добавления заметок в черновики
                /*print Html::a(AdvertsModule::t('Сохранить как черновик'), Url::to(['/advert/clear-templet']), [
                    'class' => 'btn btn-success btn-sm'
                ]);*/
                ?>

                <?= Html::a(AdvertsModule::t('Очистить'), Url::to(['clear-templet']), [
                    'class' => 'btn btn-warning btn-sm'
                ]) ?>
            <?php else: ?>
                <?= Html::submitButton(AdvertsModule::t('Сохранить изменения'), [
                    'class' => 'btn btn-primary btn-sm'
                ]); ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="clear"></div>

<?php ActiveForm::end(); ?>

<?php
if (!Yii::$app->request->isAjax) {
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
        alert('Ошибка, данные объявления не сохранилиь автоматически!');
    }
    });    
});
JS;
    $this->registerJs($js, View::POS_READY);
}

$js = <<<JS
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