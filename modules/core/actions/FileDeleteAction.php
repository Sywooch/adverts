<?php

namespace app\modules\core\actions;

use app\modules\core\base\Action;
use app\modules\core\models\ar\File;
use app\modules\core\web\Controller;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

class FileUploadAction extends Action
{
    /**
     * @inheritdoc
     */
    public function run($id = null)
    {
        $model = $this->findModel($id, Controller::MODE_WRITE);
        $file = File::findOne($id);
        return Json::encode([
            'files' => [
                'name' => $model->file_name,
                //'size' => filesize($file->full),
                'url' => $model->url,
                'thumbnailUrl' => $model->url,
                'deleteUrl' => 'image-delete?name=' . $name,
                'deleteType' => 'POST',
            ]
        ]);
    }
}