<?php

namespace app\modules\users\controllers\back;

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

class AuthController extends \app\modules\users\controllers\AuthController
{

}
