<?php

namespace app\modules\adverts\widgets;

use app\modules\core\db\ActiveRecord;
use app\modules\core\widgets\WidgetPageSize;
use app\modules\core\widgets\Wps;
use yii\helpers\ArrayHelper;
use app\modules\adverts\widgets\AdvertListWidgetPageSize;
use yii\widgets\LinkSorter;

class AdvertList extends \yii\widgets\ListView
{
    /**
     * @var ActiveRecord see GridView::$filterModel for more details
     */
    public $filterModel;

    /**
     * @var bool
     */
    public $renderFilter = true;

    /**
     * @var string
     */
    public $itemView = '@app/modules/adverts/views/front/advert/advert/index';

    /**
     * @inheritdoc
     */
    public $summaryOptions = [
        'tag' => 'span',
        'class' => 'summary',
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->showOnEmpty || $this->dataProvider->getCount() > 0) {
            $this->layout = $this->render('advert-list/index', [
                'widget' => $this,
                'tag' => ArrayHelper::remove($this->options, 'tag', 'div')
            ]);
            $content = preg_replace_callback('/{\\w+}/', function ($matches) {
                $content = $this->renderSection($matches[0]);
                return $content === false ? $matches[0] : $content;
            }, $this->layout);
        } else {
            $content = $this->renderEmpty();
        }

        echo $content;
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{widgetPageSize}':
                return $this->renderWidgetPageSize();
            default:
                return parent::renderSection($name);
        }
    }

    /**
     * @return string
     */
    public function renderWidgetPageSize()
    {
        return WidgetPageSize::widget([
            'pjaxId' => 'adverts-list-pjax',
            'widgetId' => 'adverts-list',
            'independentChanging' => true,
        ]);

    }
}