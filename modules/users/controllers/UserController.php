<?php

namespace app\modules\users\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

use roman444uk\yii\widgets\ActiveForm; 

use app\modules\users\models\User;
use app\modules\users\models\search\UserSearch;

/**
 * Class UserController
 * @package app\modules\users\controllers
 */
class UserController extends \app\modules\core\web\Controller
{
    /**
     * @var User
     */
    public $modelClass = 'app\modules\users\models\User';

    /**
     * @var UserSearch
     */
    public $modelSearchClass = 'app\modules\users\models\search\UserSearch';

    /**
     * @return mixed|string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User(['scenario' => 'newUser']);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        return $this->renderIsAjax('create', compact('model'));
    }

    /**
     * @param int $id User ID
     *
     * @throws \yii\web\NotFoundHttpException
     * @return string
     */
    public function actionChangePassword($id)
    {
        $model = User::findOne($id);
        $referrer = Yii::$app->referrer;
        $request = Yii::$app->request;

        if (!$model) {
            throw new NotFoundHttpException('User not found');
        }

        $model->scenario = 'changePassword';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($request->isAjax) {
                if ($request->headers->get('Ajax-Data-Type') === 'json') {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                } else {
                    return $this->renderAjax($view, $params);
                }
            } else if ($request->referrer != $request->getAbsoluteUrl()) {
                return $this->redirect($request->referrer);
            } else {
                return $this->redirect($referrer->getAbsoluteUrl());
            }
        }

        return $this->renderIsAjax('changePassword', compact('model'));
    }
}