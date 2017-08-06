<?php

namespace app\modules\core\widgets;

use app\modules\core\actions\BookmarkToggleAction;
use app\modules\core\db\ActiveRecord;
use app\modules\core\models\ar\Like;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;

class BookmarkButtonWidget extends Widget
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
    public $activeCssClass = 'active';

    /**
     * @var string
     */
    public $bookmarkMessage;

    /**
     * @var string
     */
    public $bookmarkedMessage;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->bookmarkMessage) {
            $this->bookmarkMessage = Yii::t('app', 'Добавить в закладки');
        }
        if (!$this->bookmarkedMessage) {
            $this->bookmarkedMessage = Yii::t('app', 'Удалить из закладок');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScripts();

        $options = [
            'class' => 'bookmark-button',
            'title' => $this->model->isBookmarkedCurrentUserInDb ? $this->bookmarkedMessage : $this->bookmarkMessage,
            'data-action' => 'bookmark',
            'data-url' => Url::to([
                '/adverts/advert/bookmark',
                'id' => $this->model->id,
            ]),
            'data-pjax' => 0,
        ];
        if ($this->model->isBookmarkedCurrentUserInDb) {
            $options['class'] .= ' active';
        }

        echo Html::tag('span','<i class="glyphicon glyphicon-star-empty"></i>', $options);
    }

    /**
     * Registers widget client scripts.
     */
    protected function registerClientScripts()
    {
        if (!self::$_initialized && !Yii::$app->user->isGuest) {
            $actionAdd = BookmarkToggleAction::ACTION_ADD;
            $actionDelete = BookmarkToggleAction::ACTION_DELETE;
            $js = <<<JS
var bookmarkLoading = false;
jQuery('{$this->primaryContainerSelector}').on('click', '[data-action=bookmark]', function(e) {
    if (bookmarkLoading) {
        return false;
    } else {
        bookmarkLoading = true;   
    }
    var self = $(this);
    $.ajax({
        url: self.attr('data-url'),
        type: 'json',
        success: function(data, textStatus, jqXHR ) {
            if (data.success) {
                if (data.action == '{$actionAdd}') {
                    self.addClass('{$this->activeCssClass}').attr('title', '{$this->bookmarkedMessage}');
                } else if (data.action == '{$actionDelete}') {
                    self.removeClass('{$this->activeCssClass}').attr('title', '{$this->bookmarkMessage}');
                }
            }
        },
        error: function() {
            alert('error. Посмотри firebug!');
        },
        complete: function() {
            bookmarkLoading = false;;
        }, 
    });
    e.preventDefault();
});
JS;
            $this->getView()->registerJs($js);

            self::$_initialized = true;
        }
    }
}