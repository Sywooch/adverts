<?php

namespace app\modules\core\widgets;

use app\modules\core\db\ActiveRecord;
use yii\base\Widget;
use yii\helpers\Html;
use Yii;

class LookButtonWidget extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

     /**
     * @var string
     */
    public $title;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->title) {
            $this->title = Yii::t('app', 'Просмотреть');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo Html::a(
            Html::tag('span',"<i class=\"glyphicon glyphicon-eye-open\"></i> <span>{$this->model->looksCount}</span>"),
            ['/adverts/advert/view', 'id' => $this->model->id],
            [
                'class' => 'look-button',
                'title' => $this->title,
                'target' => '_blank',
                'data-pjax' => 0
            ]
        );
    }
}