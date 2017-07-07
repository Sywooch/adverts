<?php

/* @var $this \yii\web\View */

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use app\modules\users\UsersModule;
use yii\bootstrap\Html;

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
            'options' => ['class' => 'navbar-nav navbar-right'],
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
                    'label' => Yii::t('app', 'Профиль'),
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
                    ]
                ],
                [
                    'label' => Yii::t('app', 'Контакты'),
                    'url' => ['/site/contact'],
                ],
                Yii::$app->user->isGuest ? (
                    ['label' => UsersModule::t('Войти', 'front'), 'url' => ['/users/auth/login']]
                ) : (
                    ['label' => UsersModule::t('Выйти', 'front'), 'url' => ['/users/auth/logout']]
                )
            ],
        ]);
    NavBar::end();
?>