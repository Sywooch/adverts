<?php

namespace app\modules\core\widgets\inputs\multiSelect;

use yii\bootstrap\InputWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class MultiselectPopup extends InputWidget
{
    const ITEMS_DISPLAY_MODE_INLINE = 'inline';
    const ITEMS_DISPLAY_MODE_NESTED = 'nested';

    const ITEMS_NESTED_MODE_DROPDOWN = 'dropdown';
    const ITEMS_NESTED_MODE_FRAMES = 'frames';

    /**
     * @var string
     */
    public $emptyText;

    /**
     * @var string
     */
    public $notEmptyText;

    /**
     * @var bool
     */
    public $multiply = true;

    /**
     * @var bool
     */
    public $likeInput = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->id = $this->options['id'];

        if (!isset($this->clientOptions['inputName'])) {
            $this->clientOptions['inputName'] = Html::getInputName($this->model, $this->attribute);
        }

        if (!isset($this->clientOptions['selectedValues'])) {
            $this->clientOptions['selectedValues'] = $this->model->{$this->attribute};
        }

        if ($this->multiply !== null) {
            $this->clientOptions['multiply'] = $this->multiply;
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $tag = ArrayHelper::remove($this->options, 'tag', 'a');

        if (!$this->multiply && $this->likeInput) {
            echo Html::activeInput('text', $this->model, $this->attribute);
        } else {
            echo Html::tag($tag, !empty($this->clientOptions['selectedValues']) ? $this->notEmptyText : $this->emptyText, $this->options);
        }

        if ($this->model && $this->attribute) {
            $inputName = Html::getInputName($this->model, $this->attribute);
            echo Html::hiddenInput($inputName, implode(',', (array) $this->model->{$this->attribute}));
        }

        $this->registerClientSript();
    }

    /**
     * Registers the client script.
     */
    protected function registerClientSript()
    {
        MultiselectPopupAsset::register($this->view);

        $options = Json::htmlEncode($this->clientOptions);
        $this->view->registerJs("$('#{$this->id}').multiselectPopup({$options})");
    }
}
