<?php

namespace app\modules\core\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class FileUploadBehavior extends Behavior
{
    /**
     * @var boolean
     */
    public $multiplyUpload = false;

    /**
     * @var string
     */
    public $relatedModel;

    /**
     * @var string
     */
    public $relatedAttribute;

    /**
     * @var string attribute name assotiated with file
     */
    public $attributeName = 'file';

    /**
     * @var string upload directory
     */
    public $savePathAlias = '@web/uploads';

    /**
     * @var array valadation scenarios
     */
    public $scenarios = ['insert', 'update', 'create'];

    /**
     * @var string file extensions
     */
    public $fileTypes = ['png', 'jpeg', 'jpg'];

    /**
     * @return string
     */
    public function getSavePath()
    {
        return \Yii::getAlias($this->savePathAlias) . DIRECTORY_SEPARATOR;
    }

    /**
     *
     * @param type $owner
     */
    public function attach($owner)
    {
        parent::attach($owner);
        if (in_array($owner->scenario, $this->scenarios)) {
            $fileValidator = \yii\validators\Validator::createValidator('file', $this->owner, $this->attributeName, ['types' => $this->fileTypes]);
            $owner->validators[] = $fileValidator;
        }
    }

    /**
     *
     * @return type
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'getFile'
        ];
    }

    /**
     *
     * @param type $event
     *
     * @return boolean
     */
    public function getFile($event)
    {
        if (in_array($this->owner->scenario, $this->scenarios) &&
            ($file = \yii\web\UploadedFile::getInstance($this->owner, $this->attributeName)))
        {
            $ext = pathinfo($file->name)['extension'];
            $newFileName = uniqid("_") . "." . $ext;
            $this->owner->setAttribute($this->attributeName, $newFileName);
            $file->saveAs($this->getSavePath() . $newFileName);
        }
        return true;

        return false;
    }

    /**
     *
     */
    public function deleteFile()
    {
        $filePath = $this->savePath . $this->owner->getAttribute($this->attributeName);
        if (@is_file($filePath)) {
            @unlink($filePath);
        }

    }
}