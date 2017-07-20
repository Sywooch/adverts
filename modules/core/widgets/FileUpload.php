<?php

namespace app\modules\core\widgets;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class FileUpload extends \dosamigos\fileupload\FileUpload
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        $input = $this->hasModel()
            ? Html::activeFileInput($this->model, $this->attribute, $this->options)
            : Html::fileInput($this->name, $this->value, $this->options);

        echo $this->useDefaultButton
            ? $this->render('file-upload/upload-button', ['input' => $input])
            : $input;

        $this->registerClientScript();
    }
}
