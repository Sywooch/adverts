<?php

namespace app\modules\core\actions;

use app\modules\core\base\Action;
use app\modules\core\models\ar\Comment;
use app\modules\core\widgets\ActiveForm;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class CommentAddAction extends Action
{
    /**
     * @inheritdoc
     */
    public function run($modelId, $modelName)
    {
        if (!Yii::$app->request->isPost) {
            throw new NotFoundHttpException();
        }

        $request = Yii::$app->request;
        $model = new Comment();
        $attributes = array_merge([
            'user_id' => Yii::$app->user->id,
            'owner_id' => $modelId,
            'owner_model_name' => $modelName,
        ], $request->post());

        if ($request->getBodyParam('ajax') && $request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model->load($attributes);
            return ActiveForm::validate($model);
        }

        if ($model->load($attributes) && $model->save()) {
            return $this->controller->redirect($request->referrer);
        }
    }
}