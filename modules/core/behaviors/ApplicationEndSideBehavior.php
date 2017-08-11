<?php

namespace app\modules\core\behaviors;

use Yii;
use yii\base\Application;
use yii\base\Behavior;

/**
 * ApplicationEndSideBehavior is a class for automatic detecting of clieтt side.
 *
 *  'as endSideBehavior' => [
 *      'class' => 'app\modules\core\behaviors\ApplicationEndSideBehavior',
 *      'frontControllerNamespace' => 'front',
 *      'frontViewPath' => 'front',
 *      'frontDefaultRoute' => 'advert/list'
 *      'backControllerNamespace' => 'front',
 *      'backViewPath' => 'front',
 *      'backDefaultRoute' => 'advert/list'
 *  ]
 */
class ApplicationEndSideBehavior extends Behavior
{
    /**
     * Cookie param name.
     */
    const LIKE_ADMIN_COOKIE_PARAM_NAME = '_like_admin_';

    /**
     * @var \yii\web\Application
     */
    public $owner;

    /**
     * @var string
     */
    public $frontControllerNamespace = 'front';

    /**
     * @var string
     */
    public $frontViewPath = 'front';

    /**
     * @var string
     */
    public $frontDefaultRoute;

    /**
     * @var string
     */
    public $backControllerNamespace = 'back';

    /**
     * @var string
     */
    public $backViewPath = 'back';

    /**
     * @var string
     */
    public $backDefaultRoute;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'beforeRequest'
        ];
    }

    /**
     * @ineritdoc
     */
    public function beforeRequest()
    {
        if (Yii::$app->user->isSuperadmin && Yii::$app->request->cookies->get(self::LIKE_ADMIN_COOKIE_PARAM_NAME)) {
            $this->owner->controllerNamespace .= "\\{$this->backControllerNamespace}";
            $this->owner->viewPath .= DIRECTORY_SEPARATOR . $this->backViewPath;
            $this->owner->defaultRoute = $this->backDefaultRoute;
        } else{
            $this->owner->controllerNamespace .= "\\{$this->frontControllerNamespace}";
            $this->owner->viewPath .= DIRECTORY_SEPARATOR . $this->frontViewPath;
            $this->owner->defaultRoute = $this->frontDefaultRoute;
        }
    }
}