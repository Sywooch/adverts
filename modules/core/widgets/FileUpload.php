<?php

namespace app\modules\core\widgets;

use app\modules\core\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

class FileUpload extends \dosamigos\fileupload\FileUpload
{
    /**
     * @inheritdoc
     */
    public $clientOptions = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->clientOptions['messages'])) {
            $this->clientOptions['messages'] = [
                'maxNumberOfFiles' => 'Загружено максимальное количество файлов',
                'acceptFileTypes' => 'Нельзя загрузить файл такого типа',
                'maxFileSize' => 'Слишком большой размер файла',
                'minFileSize' => 'Слишком маленький размер файла'
            ];
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $input = $this->hasModel()
            ? Html::activeFileInput($this->model, $this->attribute, ArrayHelper::merge($this->options, ['value' => '']))
            : Html::fileInput($this->name, $this->value, $this->options);

        echo $this->useDefaultButton
            ? $this->render('file-upload/upload-button', ['input' => $input])
            : $input;

        $this->registerClientScript();
    }
}
