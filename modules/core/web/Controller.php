<?php

namespace app\modules\core\web;

use app\modules\core\db\ActiveRecord;
use app\modules\core\widgets\ActiveForm;
use Yii;
use yii\base\Exception;
use yii\web\Response;

/**
 * Class Controller
 * @package app\modules\core\web
 */
class Controller extends \yii\web\Controller
{
    const MODE_READ = 'read';
    const MODE_WRITE = 'write';

    /**
     * @var string
     */
    public $modelName;

    /**
     * Try to perform model ajax validation.
     * @param $model
     * @return mixed
     */
    protected function performAjaxValidation($model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            Yii::$app->end();
        }
    }

    /**
     * Try to find model.
     * @param integer $id
     * @param string $mode
     * @return ActiveRecord
     * @throws Exception
     */
    public function findModel($id, $mode = self::MODE_READ)
    {
        throw new Exception('Необходимо реализовать метод findModel()');
    }
}