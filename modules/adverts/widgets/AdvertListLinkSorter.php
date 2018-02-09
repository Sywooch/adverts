<?php

namespace app\modules\adverts\widgets;

use yii\helpers\Html;
use yii\widgets\LinkSorter;

class AdvertListLinkSorter extends LinkSorter
{
    /**
     * @inheritdoc
     */
    public $title = 'Сортировать: ';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $attributes = empty($this->attributes) ? array_keys($this->sort->attributes) : $this->attributes;
        $links = [];
        foreach ($attributes as $name) {
            $links[] = $this->sort->link($name, $this->linkOptions);
        }

        echo Html::tag('span', $this->title . implode(', ', $links), [
            'class' => 'sorter'
        ]);
    }
}