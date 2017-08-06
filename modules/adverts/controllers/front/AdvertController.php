<?php

namespace app\modules\adverts\controllers\front;

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
 * @package app\modules\adverts\controllers\front
 */
class AdvertController extends Controller
{
    /**
     * @var string
     */
    public $modelName = 'app\modules\adverts\models\ar\Advert';

    /**
     * @return array
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
                'modelName' => Yii::$app->controller->action->id == 'create' ? AdvertTemplet::className() : Advert::className(),
                'findModelCallback' => function($id, $modelName) {
                    return $modelName == AdvertTemplet::className()
                        ? AdvertTemplet::getByUserId(Yii::$app->user->id) : Advert::findOne($id);
                }
            ],
            'file-delete' => [
                'class' => 'app\modules\core\actions\FileDeleteAction',
                'modelName' => Yii::$app->controller->action->id == 'create' ? AdvertTemplet::className() : Advert::className(),
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
     * Adverts list.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AdvertSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List of the published adverts.
     * @return string
     */
    public function actionPublished()
    {
        $searchModel = new AdvertSearch();
        $dataProvider = $searchModel->searchPublished();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * List of the bookmarked adverts.
     * @return string
     */
    public function actionBookmarks()
    {
        $searchModel = new AdvertSearch();
        if (!Yii::$app->user->isGuest) {
            $dataProvider = $searchModel->searchBookmarked();
        } else {
            $dataProvider = $searchModel->search(['id' => [

            ]]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creating of new advert.
     * @return Response
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $templet = AdvertTemplet::getByUserId(Yii::$app->user->id);
        $model = new Advert([
            'user_id' => $templet->user_id
        ]);
        $directPopulating = true;

        if ($model->load($request->post(), '') && $model->save() && $templet->clear()) {
            return $this->redirect(['/advert/view/', 'id' => $model->id]);
        }

        $model->copyFromTemplet($templet->attributes);
        $model->validate();

        return $this->render('create', compact('model', 'templet', 'directPopulating'));
    }

    /**
     * Viewing of advert.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, self::MODE_READ);

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

        if (Yii::$app->user->id != $model->user_id && $model->status != Advert::STATUS_ACTIVE) {
            throw new NotFoundHttpException;
        }

        return $this->render('view', [
            'model' => $model
        ]);
    }

    /**
     * @return string
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id, self::MODE_WRITE);

        return $this->renderIsAjax('view', [
            'model' => $model
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id, self::MODE_WRITE);
        $model->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param null $id
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

    /**
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionSaveTemplet()
    {
        $request = Yii::$app->request;

        if (!$request->isPost) {
            throw new NotFoundHttpException();
        }

        $model = AdvertTemplet::getByUserId(Yii::$app->user->id);
        $model->load($request->post(), '');
        $model->save();
    }

    /**
     * @return Response
     */
    public function actionClearTemplet()
    {
        $model = AdvertTemplet::getByUserId(Yii::$app->user->id);
        $model->clear();
        $model->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param int $id
     * @param string $mode
     * @return Advert
     * @throws NotFoundHttpException
     */
    protected function findModel($id, $mode = self::MODE_READ)
    {
        $model = Advert::find()
            ->withDislikesCount()
            ->withLikesCount()
            ->withLooksCount()
            ->withBookmarksCurrentUser()
            ->withLikesCurrentUser()
            ->with(['comments'])
            ->where([Advert::tableName() . '.id' => $id])
            ->one();
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('Страница не найдена'));
        }

        if (!$mode == self::MODE_WRITE) {
            return ($model && $model->user_id == Yii::$app->user->id) ? $model : false;
        }

        return $model;
    }
}