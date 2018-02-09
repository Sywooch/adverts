<?php

/* @var $this \yii\web\View */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use app\modules\core\behaviors\EndSideBehavior;
use app\modules\users\UsersModule;

?>

<?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
        echo Nav::widget([
            'options' => [
                'class' => 'navbar-nav navbar-right'
            ],
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => Yii::t('app', 'Объявления'),
                    'url' => Url::home(),
                ],
                [
                    'label' => Yii::t('app', 'Подать объявление'),
                    'url' => ['/adverts/advert/create'],
                    //'visible' => !Yii::$app->user->isGuest
                ],
                [
                    'label' => Yii::t('app', 'Закладки'),
                    'url' => ['/adverts/advert/bookmarks'],
                    'visible' => Yii::$app->user->isGuest
                ],
                [
                    'label' => '<span class="glyphicon glyphicon-user" aria-hidden="true"></span>',
                    'url' => ['/users/user/index'],
                    'visible' => !Yii::$app->user->isGuest,
                    'items' => [
                        [
                            'label' => Yii::t('app', 'Личные данные'),
                            'url' => ['/users/user/profile'],
                        ],
                        [
                            'label' => Yii::t('app', 'Закладки'),
                            'url' => ['/adverts/advert/bookmarks'],
                        ],
                        [
                            'label' => Yii::t('app', 'Опубликованные'),
                            'url' => ['/adverts/advert/published'], 
                        ],
                        [
                            'label' => Yii::t('app', 'Администрировать'),
                            'url' => ['/switch-end-side', 'side' => EndSideBehavior::BACK_END_SIDE],
                            'visible' => Yii::$app->user->isSuperadmin,
                        ],
                    ]
                ],
                [
                    'label' => 'Контакты <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>',
                    'url' => ['/site/contact'],
                ],
                Yii::$app->user->isGuest ? (
                    [
                        'label' => '<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>',
                        'url' => ['/users/auth/login'],
                    ]
                ) : (
                    [
                        'label' => 'Выйти <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>',
                        'url' => ['/users/auth/logout']
                    ]
                )
            ],
        ]);
    NavBar::end();
?>