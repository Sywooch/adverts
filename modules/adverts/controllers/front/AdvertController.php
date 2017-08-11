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
                            'bookmark', 'like', 'comment-add', 'file-upload', 'file-delete'
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
            'withFilter' => true,
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
            'withFilter' => false,
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
            'withFilter' => false,
        ]);
    }

    /**
     * Advert creating.
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

        if ($model->load($request->post(), '') && $model->save()) {
            $templet->clear();
            $templet->attachFilesToAdvert($model);
            Yii::$app->session->setFlash('success', true);
            return $this->redirect('');
        }

        $model->copyFromTemplet($templet->attributes);
        $model->validate();

        return $this->render('create', compact('model', 'templet', 'directPopulating'));
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

        return $this->render('view', [
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
        $model = $this->findModel($id, self::MODE_WRITE);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', true);
            return $this->redirect('');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Advert deleting.
     * @param integer $id
     * @return Response
     */
    public function actionDelete($id)
    {
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

    /**
     * Advert templet saving.
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
     * Advert templet clearing.
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
     * @inheritdoc
     * @return Advert|null
     * @throws NotFoundHttpException
     */
    public function findModel($id, $mode = self::MODE_READ)
    {
        if ($this->modelName == AdvertTemplet::className()) {
            return AdvertTemplet::getByUserId(Yii::$app->user->id);
        }

        $model = Advert::find()
            ->withDislikesCount()
            ->withLikesCount()
            ->withLooksCount()
            ->withBookmarksCurrentUser()
            ->withLikesCurrentUser()
            ->with(['comments.user.profile'])
            ->where([Advert::tableName() . '.id' => $id])
            ->one();

        if (!$model || $model->status != Advert::STATUS_ACTIVE
            || ($mode == self::MODE_WRITE && $model->user_id != Yii::$app->user->id)
        ) {
            throw new NotFoundHttpException(Yii::t('app', 'Страница не найдена'));
        }

        return $model;
    }
}