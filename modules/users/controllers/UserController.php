<?php

namespace app\modules\users\controllers;

use app\modules\core\web\Controller;
use app\modules\users\models\ar\User;
use app\modules\users\models\search\UserSearch;
use Yii;

/**
 * Class UserController
 */
class UserController extends Controller
{
    /**
     * Views info about user.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderIsAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Views info about user.
     * @param integer $id
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->renderIsAjax('view', [
            '$model' => $model
        ]);
    }

    /**
     * Updating info about user.
     * @param integer $id
     * @return string
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        return $this->renderIsAjax('update', [
            '$model' => $model
        ]);
    }

    /**
     * @inheritdoc
     * @return Advert|null
     * @throws NotFoundHttpException
     */
    public function findModel($id, $mode = self::MODE_READ)
    {
        $model = User::find()
            ->with(['profile.authClientUser'])
            ->where([User::tableName() . '.id' => $id])
            ->one();

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Страница не найдена'));
        }

        return $model;
    }
}