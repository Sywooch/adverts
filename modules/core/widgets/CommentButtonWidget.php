<?php

namespace app\modules\core\widgets;

use app\modules\core\db\ActiveRecord;
use app\modules\core\models\ar\Like;
use yii\base\Widget;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;

class CommentButtonWidget extends Widget
{
    /**
     * @var bool
     */
    protected static $_initialized = false;

    /**
     * @var string
     */
    public $action;

    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $primaryContainerSelector;

    /**
     * @var string
     */
    public $containerActiveClass = 'active';

    /**
     * @var string
     */
    public $titleMessage;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->titleMessage) {
            $this->titleMessage = Yii::t('app', 'Комментарии');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScripts();

        echo Html::tag('span', '<i class="glyphicon glyphicon-comment"></i> <span>' . $this->model->commentsCount . '</span>', [
            'title' => $this->titleMessage,
            'data-add-comment-url' => Url::to(['/adverts/advert/comment-add', 'id' => $this->model->id]),
            'data-pjax' => 0
        ]);
    }

    /**
     * Registers widget client scripts.
     */
    protected function registerClientScripts()
    {
        if (!self::$_initialized && !Yii::$app->user->isGuest) {
            $js = <<<JS
var loading = false;
jQuery('').on('click', '[data-action=like], [data-action=dislike]', function(e) {
    
JS;
            //$this->getView()->registerJs($js);

            self::$_initialized = true;
        }
    }
}