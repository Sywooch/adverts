<?php

namespace app\modules\core\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Class ChosenSelect свиджет для отображения выпадающих мульти списков.
 * @package app\widgets
 */
class ChosenSelect extends InputWidget
{
    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [
        //'data-placeholder' => 'Please select...',
        'multiple' => false
    ];

    /**
     * @var array select items
     */
    public $items = [];

    /**
     * @var array Plugin options
     */
    public $pluginOptions = [
        'width' => '100%',
        'placeholder_text' => 'Ничего не выбрано'
    ];

    /**
     * @var bool отображать ли элементы списка в виде дерева
     */
    public $likeTree = false;

    /**
     * @var string ключ в массиве $this->items, который отвечает за значение value элемента
     */
    public $valueKey = 'value';

    /**
     * @var string ключ в массиве $this->items, который отвечает за содержание элемента
     */
    public $contentKey = 'content';

    /**
     * @var string ключ в массиве $this->items, который отвечает за уровень вложенности элемента
     */
    public $offsetKey = 'level';

    /**
     * @var int смещение элементов относительно родительского в дереве выпадающего списка в пикселях
     */
    public $offset = 15;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Render chosen select
     * @return string|void
     */
    public function run()
    {
        $this->options['unselect'] = '';
        $this->options['options'] = [];
        $itemsLikeArray = false;
        foreach ($this->items as $key => $value) {
            if (is_numeric($key) && is_array($value)) {
                $itemsLikeArray = true;
                $opts = [
                    'disabled' => !empty($value['disabled']) ? true : false
                ];
                if ($this->likeTree) {
                    $opts['style'] = 'padding-left: ' . ($value[$this->offsetKey] * $this->offset) . 'px';
                }
                $this->options['options'][$value[$this->valueKey]] = $opts;
            }
        }
        if ($itemsLikeArray) {
            $items = ArrayHelper::map($this->items, $this->valueKey, $this->contentKey);
            $this->items = $items;
        }
        if ($this->name) {
            echo Html::dropDownList($this->name, $this->value, $this->items, $this->options);
        } else if ($this->hasModel()) {
            echo Html::activeDropDownList($this->model, $this->attribute, $this->items, $this->options);
        }
        $this->registerAssets();
    }

    /**
     * Register client assets
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        ChosenSelectAsset::register($view);
        $js = '$("#' . $this->getInputId() . '").chosen(' . $this->getPluginOptions() . ');';
        $view->registerJs($js, $view::POS_END);
    }

    /**
     * Return plugin options in json format
     * @return string
     */
    public function getPluginOptions()
    {
        return Json::encode($this->pluginOptions);
    }

    /**
     * Return input id
     */
    public function getInputId()
    {
        return $this->options['id'];
    }
}
