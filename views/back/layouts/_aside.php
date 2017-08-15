<?php

/**
 * @var $this \yii\web\View
 */

use app\modules\core\behaviors\EndSideBehavior;
use app\modules\core\widgets\SidebarMenu;
use yii\helpers\Url;

?>

<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->profile->fullName; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>

        <?php
        $route = Yii::$app->controller->route;
        echo SidebarMenu::widget([
            'activateParents' => true,
            'options' => [
                'class' => 'sidebar-menu',
                'data-widget' => 'tree',
            ],
            'items' => [
                [
                    'label' => Yii::t('app', 'Объявления'),
                    'url' => Url::home(),
                    'icon' => 'fa-files-o',
                    'items' => [
                        [
                            'label' => Yii::t('app', 'Список объявлений'),
                            'url' => ['/adverts/advert'],
                            'active' => $route == 'adverts/advert/index',
                            'labelMessages' => [
                                [
                                    'value' => 3,
                                    'class' => 'bg-green'
                                ]
                            ],
                        ],
                    ]
                ],
                [
                    'label' => Yii::t('app', 'Пользователи'),
                    'url' => ['/users/user'],
                    'icon' => 'fa-user',
                    'items' => [
                        [
                            'label' => Yii::t('app', 'Список пользователей'),
                            'url' => ['/users/user'],
                            'active' => $route == 'users/user/index',
                        ],
                    ]
                ],
            ],
        ]); ?>
    </section>
    <!-- /.sidebar -->
</aside>