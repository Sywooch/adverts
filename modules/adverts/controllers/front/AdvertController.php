<?php

namespace app\modules\adverts\controllers\front;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertTemplet;
use app\modules\adverts\models\search\AdvertSearch;
use app\modules\core\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;

/**
 * Class AdvertController
 * @package app\modules\adverts\controllers\front
 */
class AdvertController extends Controller
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
                            'bookmark', 'like', 'comment', 'upload-file'
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Список объявлений.
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
     * Список опубликованных пользователем объявлений.
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
     * Список опубликованных пользователем объявлений.
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
     * Создание объявления.
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

        return $this->render('create', compact('model', 'directPopulating'));
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id, self::MODE_READ);

        if (Yii::$app->user->id != $model->user_id && $model->status != Advert::STATUS_ACTIVE) {
            throw new NotFoundHttpException;
        }

        return $this->renderIsAjax('view', [
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
        $model = AdvertTemplet::getByUserId(Yii::$pp->user->id);
        $model->clear();
        $model->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     *
     */
    public function actionBookmark($id)
    {

    }

    /**
     *
     */
    public function actionLike($id, $value)
    {

    }

    /**
     *
     */
    public function actionUploadFile($id)
    {

    }

    /**
     *
     */
    public function actionComment($id)
    {

    }

    /**
     * Test action.
     */
    public function actionVk()
    {
        $wall = file_get_contents("https://api.vk.com/method/wall.get?owner_id=-120504421&filter=others");
        $wall = json_decode($wall);

        foreach ($wall->response as $item) {
            if (is_object($item)) {
                $advert = new Advert;
                $advert->setScenario(Advert::SCENARIO_CREATE_FROM_SERVICE);
                $advert->content = $item->text;
                $advert->created_at = $item->date;
                $advert->save();

                if (isset($item->attachments)) {
                    foreach ($item->attachments as $a) {
                        $type = $a->type;
                        $f = $a->$type;
                        
                        $file = new \common\models\Image;
                        $file->remote_url = $f->src_big;
                        $file->created_at = $f->created;
                        $file->save();

                        $file->attachOwner($advert->id, 'Advert', 'files');
                    }
                }
            }
        }
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