<?php

namespace app\modules\core\behaviors;

use Yii;
use yii\base\Application;
use yii\base\Behavior;
use yii\base\Module;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;

/**
 * EndSideBehavior is a class for automatic detecting of client side.
 */
class EndSideBehavior extends Behavior
{
    const BACK_END_SIDE = 'back';
    const FRONT_END_SIDE = 'front';

    const LIKE_ADMIN_COOKIE_PARAM_NAME = '_like_admin';

    /**
     * @var Module
     */
    public $owner;

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
        $this->setEndSideFolders();
    }

    /**
     * Sets folders according to the end site.
     */
    public function setEndSideFolders()
    {
        $path = Yii::$app->user->isSuperadmin && Yii::$app->request->cookies->get(self::LIKE_ADMIN_COOKIE_PARAM_NAME)
            ? self::BACK_END_SIDE : self::FRONT_END_SIDE;

        $this->owner->controllerNamespace .= "\\{$path}";
        $this->owner->viewPath .= DIRECTORY_SEPARATOR . $path;
        // TODO
        // Yii::setAlias('@mail', Yii::getAlias("@app/mail{$path}"));
    }

    /**
     * Switches end side.
     */
    public function switchEndSide($side = null)
    {
        $cookies = Yii::$app->response->cookies;
        if ($side == self::FRONT_END_SIDE) {
            $cookies->remove(self::LIKE_ADMIN_COOKIE_PARAM_NAME);
            return true;
        } else if ($side == self::BACK_END_SIDE) {
            $cookies->add(new Cookie([
                'name' => self::LIKE_ADMIN_COOKIE_PARAM_NAME,
                'value' => self::LIKE_ADMIN_COOKIE_PARAM_NAME,
            ]));
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function getIsEndSideBack()
    {
        return Yii::$app->request->cookies->has(self::LIKE_ADMIN_COOKIE_PARAM_NAME);
    }

    /**
     * @return bool
     */
    public function getIsEndSideFront()
    {
        return !Yii::$app->request->cookies->has(self::LIKE_ADMIN_COOKIE_PARAM_NAME);
    }
}
