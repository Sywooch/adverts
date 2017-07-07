<?php

namespace app\modules\users\controllers\front;

use app\modules\core\widgets\ActiveForm;
use app\modules\users\components\UserAuthEvent;
use app\modules\users\models\ar\PasswordRestoreToken;
use app\modules\users\models\form\ChangePasswordForm;
use app\modules\users\models\form\EmailConfirmForm;
use app\modules\users\models\form\LoginForm;
use app\modules\users\models\form\RegistrationForm;
use app\modules\users\models\form\PasswordRestoreForm;
use app\modules\users\models\ar\User;
use app\modules\users\UsersModule;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class AuthController
 */
class AuthController extends \app\modules\core\web\Controller
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
                        'actions' => ['login', 'registration', 'email', 'email-confirm', 'password-restore', 'captcha', 'change-password'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout', 'profile', 'change-password'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Страница авторизации.
     * @return Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', compact('model'));
    }

    /**
     * Logout from site.
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Регистрация.
     * @return string|Response
     */
    public function actionRegistration()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $session = Yii::$app->session;
        $registeredUser = User::findOne($session->getFlash('registeredUserId'));
        $model = new RegistrationForm();
        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->request->post()) && $user = $model->registerUser()) {
            $session->setFlash('registeredUserId', $user->id);
            return $this->redirect('');
        }

        return $this->render('registration', compact('model', 'registeredUser'));
    }

    /**
     * Form to recover password
     * @return string|\yii\web\Response
     */
    public function actionPasswordRestore()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $request = Yii::$app->request;
        $sendingError = false;
        $model = new PasswordRestoreForm();
        $this->performAjaxValidation($model);

        if ($model->load($request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('passwordRestoreEmailSend', true);
                return $this->redirect('');
            } else {
                $sendingError = false;
            }
        }

        return $this->render('password-restore', compact('model', 'sendingError'));
    }

    /**
     *
     */
    public function actionChangePassword()
    {
        $request = Yii::$app->request;
        $passwordChanged = Yii::$app->session->getFlash('passwordChanged');
        $model = new ChangePasswordForm();

        if (Yii::$app->user->isGuest) {
            $user = User::findByPasswordRestoreToken($request->get('token'));
            if (!$user && !$passwordChanged) {
                throw new NotFoundHttpException();
            }
            $model->user = $user;
            $model->setScenario(ChangePasswordForm::SCENARIO_RESTORE_VIA_EMAIL);
        } else {
            if ($request->get('token')) {
                return $this->redirect('/users/auth/change-password');
            }
            $model->user = Yii::$app->user->identity;
        }

        $this->performAjaxValidation($model);

        if ($model->load($request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('passwordChanged', true);
            return $this->redirect('/users/auth/change-password');
        }

        return $this->render('change-password', compact('model', 'passwordChanged'));
    }

    /**
     * Email confirmation.
     * @param string $token
     * @throws \yii\web\NotFoundHttpException
     * @return string|\yii\web\Response
     */
    public function actionEmailConfirm($token)
    {
        if ($model = User::findByEmailConfirmToken($token)) {
            Yii::$app->user->login($model);
            return $this->render('email-confirm', compact('model'));
        }

        throw new NotFoundHttpException();
    }
}
