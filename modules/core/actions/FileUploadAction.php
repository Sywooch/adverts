<?php

namespace app\modules\core\actions;

use app\modules\core\base\Action;
use app\modules\core\db\ActiveRecord;
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
        Yii::$app->response->format = Response::FORMAT_JSON;

        /** @var Controller $controller */
        $controller = $this->controller;
        if ($owner = Yii::$app->request->get('owner')) {
            $controller->modelName = $owner;
        }
        $model = $controller->findModel($id, $controller::MODE_WRITE);
        $file = File::upload($model);

        if (!$file->hasErrors()) {
            return [
                'success' => true,
                'file' => [
                    'file_name' => $file->file_name,
                    'url' => $file->url,
                    'deleteUrl' => Url::to(['file-delete', 'name' => $file->file_name]),
                ]
            ];
        } else {
            return [
                'success' => false,
                'errors' => $file->getErrors()
            ];
        }
    }
}