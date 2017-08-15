<?php

namespace app\modules\users\controllers;

use app\modules\authclient\clients\ClientTrait;
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
use yii\authclient\BaseClient;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class AuthController
 */
class AuthController extends \app\modules\core\web\Controller
{
    /**
     * Logout from site.
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
