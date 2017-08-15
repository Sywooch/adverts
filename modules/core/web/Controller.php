<?php

namespace app\modules\core\web;

use app\modules\core\db\ActiveRecord;
use app\modules\core\widgets\ActiveForm;
use Yii;
use yii\base\Exception;
use yii\web\Response;
use yii\widgets\LinkPager;

/**
 * Class Controller
 * @package app\modules\core\web
 */
class Controller extends \yii\web\Controller
{
    const MODE_READ = 'read';
    const MODE_WRITE = 'write';

    /**
     * @var string
     */
    public $modelName;

    /**
     * @var string ajax layout file (does not required). If does not set then response would render without layout any file.
     */
    public $layoutAjax;

    /**
     * Render ajax or usual depends on request*
     * @param string $view
     * @param array $params
     * @return string|\yii\web\Response
     */
    public function renderIsAjax($view, $params = [])
    {
        $request = Yii::$app->request;

        if ($request->isAjax) {
            if ($request->headers->get('Ajax-Data-Type') == 'json') {
                Yii::$app->response->format = Response::FORMAT_JSON;

                if (isset($params['searchModel']) && isset($params['dataProvider'])) {
                    return json_encode([
                        'list' => $this->_searchModel->buildModels(),
                        'pagination' => ($this->paginationAsHTML)
                            ? LinkPager::widget([
                                'pagination' => $dataProvider->getPagination()
                            ])
                            : $dataProvider->getPagination()
                    ]);
                } else {
                    return json_encode($params);
                }
            } else {
                return $this->renderAjax($view, $params);
            }
        } else {
            return $this->render($view, $params);
        }
    }

    /**
     * @inheritdoc
     */
    public function renderAjax($view, $params = [])
    {
        $layoutAjax = null;
        if ($this->layoutAjax !== false) {
            $layoutAjax = $this->layoutAjax ? : $this->module->layoutAjax;
        }

        if ($layoutAjax && !Yii::$app->getRequest()->getHeaders()->get('X-Pjax')) {
            $controllerLayout = $this->layout;
            $moduleLayout = $this->module->layout;

            $this->layout = $this->layoutAjax;
            $this->module->layout = $this->module->layoutAjax;

            $content = $this->renderContent(
                $this->getView()->renderAjax($view, $params, $this)
            );

            $this->layout = $controllerLayout;
            $this->module->layout = $moduleLayout;

            return $content;
        } else {
            return $this->getView()->renderAjax($view, $params, $this);
        }
    }

    /**
     * Try to perform model ajax validation.
     * @param $model
     * @return mixed
     */
    protected function performAjaxValidation($model)
    {
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            Yii::$app->end();
        }
    }

    /**
     * Adding flash message depending on the request type.
     * @param string $key
     * @param mixed $value
     */
    protected function addFlashMessage($key, $value)
    {
        if (!Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash($key, $value);
        }
    }

    /**
     * Try to find model.
     * @param integer $id
     * @param string $mode
     * @return ActiveRecord
     * @throws Exception
     */
    public function findModel($id, $mode = self::MODE_READ)
    {
        throw new Exception('Необходимо реализовать метод findModel()');
    }
}