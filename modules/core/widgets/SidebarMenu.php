<?php

namespace app\modules\core\widgets;

use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class SidebarMenu extends Nav
{
    /**
     * @var array
     */
    public $itemOptions = ['class' => 'treeview'];

    /**
     * @var array
     */
    public $subMenuOptions = ['class' => 'treeview-menu'];

    /**
     * @var array
     */
    public $subItemOptions = [];

    /**
     * @var array
     */
    public $subItemLinkOptions = [];

    /**
     * @inheritdoc
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return $item;
        }
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }

        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
        $label = $this->renderIcon($item);
        $label .= Html::tag('span', $encodeLabel ? Html::encode($item['label']) : $item['label']);
        $label .= Html::tag('span', '<i class="fa fa-angle-left pull-right"></i>', [
            'class' => 'pull-right-container'
        ]);
        $options = ArrayHelper::merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);

        if (isset($item['active'])) {
            $active = ArrayHelper::remove($item, 'active', false);
        } else {
            $active = $this->isItemActive($item);
        }

        if (empty($items)) {
            $items = '';
        } else {
            if (is_array($items)) {
                $items = $this->isChildActive($items, $active);
                $items = $this->renderTreeviewMenu($items, $item);
            }
        }

        if ($active) {
            Html::addCssClass($options, 'active');
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }

    /**
     * @inheritdoc
     */
    protected function renderTreeviewMenu($items, $parentItem)
    {
        $menu = [];
        foreach ($items as $item) {
            $options = isset($item['options']) ? ArrayHelper::merge($this->subItemOptions, $item['options']) : $this->subItemOptions;
            $linkOptions = ArrayHelper::getValue($item,'linkOptions', []);
            if (isset($item['icon'])) {
                $iconOptions = ['class' => 'fa'];
                Html::addCssClass($iconOptions, $item['icon']);
                $icon = Html::tag('i', '', $iconOptions);
            } else {
                $icon = '';
            }
            $labelMessages = $this->renderLabelMessages($item);
            $menu[] = Html::tag('li', Html::a($icon . $item['label'] . $labelMessages, $item['url'], $linkOptions), $options);
        }
        return Html::tag('ul', implode('', $menu), $this->subMenuOptions);
    }

    /**
     * @param array $item
     * @return string
     */
    protected function renderIcon($item)
    {
        if (isset($item['icon'])) {
            $iconOptions = ['class' => 'fa'];
            Html::addCssClass($iconOptions, $item['icon']);
            return Html::tag('i', '', $iconOptions);
        }
        return '';
    }

    /**
     * @param array $item
     * @return string
     */
    protected function renderLabelMessages($item)
    {
        if (isset($item['labelMessages'])) {
            $messages =[];
            foreach ($item['labelMessages'] as $label) {
                $options = ['class' => 'label pull-right'];
                Html::addCssClass($options, $label['class']);
                $messages[] = Html::tag('small', $label['value'], $options);
            }
            return Html::tag('span', implode('', $messages), ['class' => 'pull-right-container']);
        }
        return '';
    }
}