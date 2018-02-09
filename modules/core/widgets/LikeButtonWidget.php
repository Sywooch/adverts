<?php

namespace app\modules\core\widgets;

use app\modules\core\db\ActiveRecord;
use app\modules\core\models\ar\Like;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\helpers\Url;

class LikeButtonWidget extends Widget
{
    const ACTION_LIKE = 'like';
    const ACTION_DISLIKE = 'dislike';

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
    public $likeMessage;

    /**
     * @var string
     */
    public $likedMessage;

    /**
     * @var string
     */
    public $dislikeMessage;

    /**
     * @var string
     */
    public $dislikedMessage;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->likeMessage) {
            $this->likeMessage = Yii::t('app', 'Нравится');
        }
        if (!$this->dislikeMessage) {
            $this->dislikeMessage = Yii::t('app', 'Не нравится');
        }
        if (!$this->likedMessage) {
            $this->likedMessage = Yii::t('app', 'Вам понравилась запись');
        }
        if (!$this->dislikedMessage) {
            $this->dislikedMessage = Yii::t('app', 'Вам не понравилась запись');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScripts();

        $valueContainerTag = Html::tag('span', $this->action == self::ACTION_LIKE ? $this->model->likesCount : $this->model->dislikesCount, [
            'data-action' => 'value'
        ]);
        $glyphiconIconTag = Html::tag('i', null, [
            'class' => ($this->action == self::ACTION_LIKE) ? 'glyphicon glyphicon-thumbs-up' : 'glyphicon glyphicon-thumbs-down'
        ]);

        $liked = $this->action == self::ACTION_LIKE ? $this->model->isLikedCurrentUser : $this->model->isDislikedCurrentUser;

        $linkOptions = [
            'class' => 'like-button' . ($liked ? " {$this->activeCssClass}" : null),
            'title' => $this->action == self::ACTION_LIKE
                ? ($liked ? $this->likedMessage : $this->likeMessage) : ($liked ? $this->dislikedMessage : $this->dislikeMessage),
        ];

        if (!Yii::$app->user->isGuest) {
            $model = $this->model;
            $linkOptions = ArrayHelper::merge($linkOptions, [
                'data-url' => Url::to([
                    '/adverts/advert/like',
                    'owner_id' => $this->model->id,
                    'value' => $this->action == self::ACTION_LIKE ? Like::LIKE_VALUE : Like::DISLIKE_VALUE
                ]),
                'data-action' => $this->action,
                'data-owner' => $model::shortClassName(),
                'data-owner-id' => $this->model->id,
                'data-pjax' => 0,
            ]);
        }

        echo Html::tag('span', "{$glyphiconIconTag} {$valueContainerTag}", $linkOptions);
    }

    /**
     * Registers widget client scripts.
     */
    protected function registerClientScripts()
    {
        if (!self::isInitialized() && !Yii::$app->user->isGuest) {
            $js = <<<JS
var likeLoading = false;
jQuery('{$this->primaryContainerSelector}').on('click', '[data-action=like], [data-action=dislike]', function(e) {
    if (likeLoading) {
        return false;
    } else {
        likeLoading = true;   
    }
    var self = $(this);
    var owner = self.attr('data-owner');
    var ownerId = self.attr('data-owner-id');
    $.ajax({
        url: self.attr('data-url'),
        type: 'json',
        success: function(data, textStatus, jqXHR ) {
            if (data.success) {
                if (data.like != 'none') {
                    var likeLink = $('[data-action=like][data-owner-id=' + ownerId + ']');
                    var valueContainer = likeLink.find('[data-action=value]');
                    var value = valueContainer.html();
                    if (data.like == 'plus') {
                        value++;
                        likeLink.addClass('active').attr('title', '{$this->likedMessage}');
                    } else if (data.like == 'minus') {
                        value--;
                        likeLink.removeClass('active').attr('title', '{$this->likeMessage}');
                    }
                    valueContainer.html(value);
                }
                if (data.dislike != 'none') {
                    var dislikeLink = $('[data-action=dislike][data-owner-id=' + ownerId + ']');
                    var valueContainer = dislikeLink.find('[data-action=value]');
                    var value = valueContainer.html();
                    if (data.dislike == 'plus') {
                        value++;
                        dislikeLink.addClass('{$this->activeCssClass}').attr('title', '{$this->dislikedMessage}');
                    } else if (data.dislike == 'minus') {
                        value--;
                        dislikeLink.removeClass('{$this->activeCssClass}').attr('title', '{$this->dislikeMessage}');
                    }
                    valueContainer.html(value);
                }
            }
        },
        error: function() {
            alert('error. Посмотри firebug!');
        },
        complete: function() {
            likeLoading = false;;
        }, 
    });
    e.preventDefault();
});
JS;
            $this->getView()->registerJs($js);
            self::initialize();
        }
    }
}