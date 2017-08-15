<?php

namespace app\modules\adverts\controllers\back;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertTemplet;
use app\modules\adverts\models\search\AdvertSearch;
use app\modules\core\models\ar\File;
use app\modules\core\models\ar\Look;
use app\modules\core\web\Controller;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * Class AdvertController
 */
class AdvertController extends \app\modules\adverts\controllers\AdvertController
{
    /**
     * @inheritdoc
     * @return Advert|null
     * @throws NotFoundHttpException
     */
    public function findModel($id, $mode = self::MODE_READ)
    {
        $model = Advert::find()
            ->withDislikesCount()
            ->withLikesCount()
            ->withLooksCount()
            ->withBookmarksCurrentUser()
            ->withLikesCurrentUser()
            ->with(['comments.user.profile'])
            ->where([Advert::tableName() . '.id' => $id])
            ->one();

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Страница не найдена'));
        }

        return $model;
    }
}