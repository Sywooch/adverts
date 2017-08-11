<?php

namespace app\modules\core\actions;

use app\modules\core\base\Action;
use app\modules\core\models\ar\File;
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
        Yii::$app->response->format = Response::FORMAT_JSON;

        //$controller = $this->controller;
        //$controller->findModel($id, $controller::MODE_WRITE);

        $success = false;
        if ($file = File::findOne($id)) {
            $success = (bool) $file->delete();
        }

        return [
            'success' => $success
        ];
    }
}