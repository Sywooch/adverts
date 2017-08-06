<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\adverts\models\ar\Advert
 * @var $owner \app\modules\users\models\ar\User
 * @var $profile \app\modules\users\models\ar\Profile
 * @var $renderPartial bool
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

use app\modules\users\components\GhostHtml;
use app\modules\users\models\rbacDB\Role;
use app\modules\users\models\ar\User;
use app\modules\users\UsersModule;


$this->title = $model->username;

?>
