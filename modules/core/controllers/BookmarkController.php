<?php

namespace app\modules\core\controllers;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertTemplet;
use app\modules\adverts\models\search\AdvertSearch;
use app\modules\core\models\ar\File;
use app\modules\core\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * Class AdvertController
 * @package app\modules\adverts\controllers\front
 */
class BookmarkController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'published', 'bookmarks'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'create', 'validate', 'save-templet', 'clear-templet', 'update', 'delete',
                            'bookmark', 'like', 'comment', 'file-upload', 'file-delete'
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param integer $id
     * @param string $entity
     * @return string
     */
    public function actionIndex($id, $entity)
    {

    }

    /**
     * @param int $id
     * @param string $mode
     * @return Advert
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $mode = self::MODE_READ)
    {
        if (!$model = Advert::findOne($id)) {
            throw new NotFoundHttpException(Yii::t('Страница не найдена'));
        }

        if (!$mode == self::MODE_WRITE) {
            return ($model && $model->user_id == Yii::$app->user->id) ? $model : false;
        }

        return $model;
    }
}