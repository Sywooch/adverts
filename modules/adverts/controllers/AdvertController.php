<?php

namespace app\modules\adverts\controllers;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertTemplet;
use app\modules\adverts\models\search\AdvertSearch;
use app\modules\core\behaviors\controllers\WidgetPageSizeBehavior;
use app\modules\core\models\ar\File;
use app\modules\core\models\ar\Look;
use app\modules\core\web\Controller;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;

/**
 * Class AdvertController
 */
class AdvertController extends Controller
{
    /**
     * @var string
     */
    public $modelName = 'app\modules\adverts\models\ar\Advert';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'bookmark' => [
                'class' => 'app\modules\core\actions\BookmarkToggleAction',
                'modelName' => Advert::className()
            ],
            'comment-add' => [
                'class' => 'app\modules\core\actions\CommentAddAction',
                'modelName' => Advert::className()
            ],
            'comment-delete' => [
                'class' => 'app\modules\core\actions\CommentDeleteAction',
                'modelName' => Advert::className()
            ],
            'file-upload' => [
                'class' => 'app\modules\core\actions\FileUploadAction',
                'modelName' => Yii::$app->request->get('owner'),
            ],
            'file-delete' => [
                'class' => 'app\modules\core\actions\FileDeleteAction',
                'modelName' => Yii::$app->request->get('owner'),
            ],
            'like' => [
                'class' => 'app\modules\core\actions\LikeAction',
                'modelName' => Advert::className()
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'widgetPageSize' => WidgetPageSizeBehavior::className()
        ]);
    }

    /**
     * Adverts list.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AdvertSearch();

        $searchParams = Yii::$app->request->queryParams;
        $dataProvider = $searchModel->search($searchParams);

        return $this->renderIsAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'withFilter' => true,
        ]);
    }

    /**
     * Advert wiewing.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, self::MODE_READ);

        if (Yii::$app->getIsEndSideFront()) {
            $lookModelAttributes = [
                'user_id' => Yii::$app->user->id,
                'owner_id' => $model->id,
                'owner_model_name' => Advert::shortClassName()
            ];
            if (!$lookModel = Look::findOne($lookModelAttributes)) {
                $lookModel = new Look($lookModelAttributes);
            }
            $lookModel->plus();
            $model->looksCount++;
        }

        return $this->renderIsAjax('view', [
            'model' => $model
        ]);
    }

    /**
     * Advert updating.
     * @param integer $id
     * @return string|Response
     */
    public function actionUpdate($id)
    {
        /** @var Advert $model */
        $model = $this->findModel($id, self::MODE_WRITE);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (!$model->published && $model->status = Advert::STATUS_ACTIVE) {
                Yii::$app->get('vkPublisher')->publishAdvert($model);
            }

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => true,
                    'redirect-url' => Url::to(''),
                ];
            } else {
                $this->addFlashMessage('success', true);
                return $this->redirect('');
            }
        }

        return $this->renderIsAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Advert deleting.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        throw new NotFoundHttpException();
        $model = $this->findModel($id, self::MODE_WRITE);
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Advert validating.
     * @param integer|null $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionValidate($id = null)
    {
        $request = Yii::$app->getRequest();
        if (!$request->isAjax) {
            throw new NotFoundHttpException();
        }

        if ($id) {
            $model = $this->findModel($id);
        } else {
            $model = new Advert();
        }
        $model->load($request->post());

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
    }
}