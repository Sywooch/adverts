<?php 

use yii\web\View;
use yii\helpers\Url;
use yii\web\JsExpression;

use roman444uk\files\models\File;

$init = <<<JS
function() {
    $('#file-file').remove();
    $('#advert-file-upload-form .button').click(function() {
        $('#advert-file-upload-form').click();
    });
}
JS;

$addedfile = <<<JS
function(file) {

}
JS;


$uploadprogress = <<<JS
function(event, percent) {
    if (event.status == 'uploading') {
        $('#upload-file-progress').progressbar("value", percent);
    }
}
JS;

$success = <<<JS
function(event, files) {
    $('#advert-uploaded-files').prepend(event.xhr.responseText);
}
JS;

$error = <<<JS
function(event) {

}
JS;

echo roman444uk\dropzoneFileUploader\DropzoneFileUploader::widget([
    'id' => 'advert-file-upload-form',
    'renderContainer' => false,
    'clientOptions' => [
        'url' => Url::to('/file/upload'),
        'paramName' => 'File[file]',
        'clickable' => true,
        'previewsContainer' => '#advert-uploaded-files',
        'previewTemplate' => $this->render('_file', ['model' => new File]),
        'init' => new JsExpression($init),
        'addedfile' => new JsExpression($addedfile),
        'success' => new JsExpression($success),
        'uploadprogress' => new JsExpression($uploadprogress),
    ],
]) ?>

<?= \roman444uk\jui\ProgressBar::widget([
    'id' => 'upload-file-progress',
    'clientOptions' => [
        //'value' => 50
    ],
    'textValue' => [
        'id' => 'upload-file-progress-text'
    ]
]) ?>