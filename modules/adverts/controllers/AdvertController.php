<?php

namespace app\modules\adverts\controllers;

use app\modules\adverts\models\Advert;
use app\modules\core\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AdvertController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return \yii\helpers\ArrayHelper::merge(parent::actions(), [
            'bookmarks' => [
                'class' => 'roman444uk\yii\components\actions\ListAction',
                'searchModel' => function() {
                    return new $this->modelSearchClass([
                        'search_bookmarks' => true,
                        'bookmark_user_id' => Yii::$app->user->id
                    ]);
                },
            ],
            'delete' => [
                'class' => 'roman444uk\yii\components\actions\DeleteAction',
                'model' => $this->modelClass,
            ],
            'like' => [
                'class' => 'roman444uk\likes\components\LikeAction',
                'target' => 'Advert',
            ],
            'list' => [
                'class' => 'roman444uk\yii\components\actions\ListAction',
                'searchModel' => function() {
                    return new $this->modelSearchClass([
                        'status' => Advert::STATUS_ACTIVE
                    ]);
                },
                'directPopulating' => true
            ],
            'published' => [
                'class' => 'roman444uk\yii\components\actions\ListAction',
                'searchModel' => function() {
                    return new $this->modelSearchClass([
                        'user_id' => Yii::$app->user->id,
                        'is_templet' => 0
                    ]);
                },
            ],
            'toggle-bookmark' => [
                'class' => 'roman444uk\bookmarks\components\ToggleBookmarkAction',
                'target' => 'Advert',
            ],
            'update' => [
                'class' => 'roman444uk\yii\components\actions\UpdateAction',
                'model' => $this->modelClass,
            ]
        ]);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return Response
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = Advert::getTemplet();
        $directPopulating = true;

        if ($request->isPost) {
            $model->is_templet = 0;
            if ($this->loadAndSaveModel($model, $request->post())) {
                return $this->redirect(['/advert/view/', 'id' => $model->id]);
            } else {
                $model->saveTemplet();
            }
        }

        $model->validate();

        return $this->renderIsAjax('create', compact('model', 'directPopulating'));
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->id != $model->user_id && $model->status != Advert::STATUS_ACTIVE) {
            throw new NotFoundHttpException;
        }

        $request = Yii::$app->request;

        $params = [
            'model' => $model
        ];

        return $this->renderIsAjax('view', $params);
    }

    /**
     * @return string
     */
    public function actionUpdate()
    {
        return $this->render('update');
    }

    /**
     * @param $id
     */
    public function actionDelete($id)
    {
        $model = Advert::findOne($id);
        $model->delete();
    }

    /**
     * @return Response
     */
    public function actionClearTemplet()
    {
        $model = Advert::getTemplet();
        $model->clearTemplet();
        
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
        
        $model = $this->findModel($id);
        $model->load($request->post());

        if ($model->isTemplet) {
            $model->setScenario(Advert::SCENARIO_UPDATE_TEMPLET);
            $model->save();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
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
}