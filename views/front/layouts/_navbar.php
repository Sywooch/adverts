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
        'brandLabel' => 'Объявления НОВОРОССИИ',
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
                    // <span class="glyphicon glyphicon-list"></span>
                    'label' => Yii::t('app', 'Объявления'),
                    'url' => Url::home(),
                ],
                [
                    //<span class="glyphicon glyphicon-plus"></span>
                    'label' => Yii::t('app', 'Добавить'),
                    'url' => ['/adverts/advert/create'],
                    //'visible' => !Yii::$app->user->isGuest
                ],
                [
                    //<span class="glyphicon glyphicon-star"></span>
                    'label' => Yii::t('app', 'Закладки'),
                    'url' => ['/adverts/advert/bookmarks'],
                    'visible' => Yii::$app->user->isGuest
                ],
                [
                    // <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                    'label' => 'Кабинет',
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
                    // <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>
                    'label' => 'Контакты',
                    'url' => ['/site/contact'],
                ],
                Yii::$app->user->isGuest ? (
                    [
                        // <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
                        'label' => 'Войти',
                        'url' => ['/users/auth/login'],
                    ]
                ) : (
                    [
                        // <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
                        'label' => 'Выйти',
                        'url' => ['/users/auth/logout']
                    ]
                )
            ],
        ]);
    NavBar::end();
?>