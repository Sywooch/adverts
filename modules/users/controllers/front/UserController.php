<?php

namespace app\modules\users\controllers\front;

use app\modules\core\widgets\ActiveForm;
use app\modules\users\models\ar\User;
use app\modules\users\models\form\ChangePasswordForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Class UserController
 * @package app\modules\users\controllers
 */
class UserController extends \app\modules\core\web\Controller
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
                        'actions' => ['view'],
                        'roles' => ['?', '@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['profile'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Views info about user.
     * @param int $id
     * @return string
     */
    public function actionView($id)
    {
        return $this->render('view');
    }

    /**
     * Renders user profile form.
     * @return string
     */
    public function actionProfile()
    {
        $request = Yii::$app->request;
        $model = User::find()->with('profile')->where(['id' => Yii::$app->user->id])->one();
        $profile = $model->profile;
        $changePasswordForm = new ChangePasswordForm(['user' => $model]);

        $userLoaded = $model->load($request->post());
        $profileLoaded = $profile->load($request->post());

        if ($request->isAjax && $request->getBodyParam('ajax')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($changePasswordForm->load($request->post())) {
                print json_encode(ActiveForm::validate($changePasswordForm));
            } else if ($userLoaded || $profileLoaded) {
                print json_encode(ActiveForm::validate($model, $profile));
            }
            Yii::$app->end();
        }

        if ($changePasswordForm->load($request->post()) and $changePasswordForm->changePassword()) {
            Yii::$app->session->setFlash('passwordChangedSuccess', true);
            return $this->redirect('');
        }

        if (($userLoaded || $profileLoaded) && ($model->save() && $profile->save())) {
            Yii::$app->session->setFlash('profileChangedSuccess', true);
            return $this->redirect('');
        }

        return $this->render('profile', compact('model', 'changePasswordForm', 'profile'));
    }
}