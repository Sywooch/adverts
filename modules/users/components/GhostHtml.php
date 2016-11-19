<?php
/**
 * @var $this yii\web\View
 */

namespace app\modules\users\components;

use yii;
use yii\helpers\Html;

use app\modules\users\models\User;

/**
 * Class GhostHtml
 *
 * Show elements only to those, who can access to them
 *
 * @package webvimark\modules\UserManagement\components
 */
class GhostHtml extends Html
{
   /**
    * Hide link if user hasn't access to it
    * 
    * @inheritdoc
    */
   public static function a($text, $url = null, $options = [])
   {
        if (in_array($url, [null, '', '#'])) {
            return parent::a($text, $url, $options);
        }
        
        return User::canRoute($url) ? parent::a($text, $url, $options) : '';
   }
}