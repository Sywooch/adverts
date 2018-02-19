<?php

namespace app\modules\geography\controllers\front;

use yii\web\NotFoundHttpException;
use app\modules\geography\models\search\GeographySearch;

class GeographyController extends \app\modules\core\web\Controller
{
    /**
     *
     */
    public function actionIndex()
    {
        if (!Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        GeographySearch::getCityListGroupedByRegion();
    }
}