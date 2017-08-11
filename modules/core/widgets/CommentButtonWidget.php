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
        $commentsCount = $this->model->commentsCount !== null ? $this->model->commentsCount : count($this->model->comments);
        echo Html::tag('span', '<i class="glyphicon glyphicon-comment"></i> <span>' . $commentsCount . '</span>', [
            'title' => $this->titleMessage,
            'data-add-comment-url' => Url::to(['/adverts/advert/comment-add', 'id' => $this->model->id]),
            'data-pjax' => 0
        ]);
    }
}