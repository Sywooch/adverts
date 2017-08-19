<?php

/**
 * @var $this \yii\web\View
 */

use app\modules\core\behaviors\EndSideBehavior;
use yii\helpers\Url;

?>

<header class="main-header">
    <a href="../../index2.html" class="logo">
        <span class="logo-mini"><b>A</b>LT</span>
        <span class="logo-lg"><b>Admin</b>LTE</span>
    </a>

    <nav class="navbar navbar-static-top">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="<?= Url::to(['/switch-end-side', 'side' => EndSideBehavior::FRONT_END_SIDE]); ?>">
                        <i class="fa fa-home"></i>
                    </a>
                </li>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!--<img src="../../dist/img/user2-160x160.jpg" class="user-image" alt="User Image">-->
                        <span class="hidden-xs"><?= Yii::$app->user->identity->profile->fullName; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <!--<img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                            <p>
                                <?= Yii::$app->user->identity->profile->fullName; ?>
                                <small>Member since Nov. 2012</small>
                            </p>
                        </li>
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="#" class="btn btn-default btn-flat"><?= Yii::t('app', 'Выйти') ?></a>
                            </div>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>