<?php

namespace app\modules\core\actions;

use app\modules\core\base\Action;
use Yii;
use yii\helpers\Url;
use yii\web\Response;

class FileDeleteAction extends Action
{
    /**
     * @inheritdoc
     */
    public function run($id = null)
    {
        $model = $this->findModel($id, self::MODE_WRITE);
        if ($file = File::upload($model)) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'file' => [
                    'file_name' => $file->file_name,
                    'url' => $file->url,
                    'deleteUrl' => Url::to(['file-delete', 'name' => $file->file_name]),
                ]
            ];
        }

        return [];
    }
}