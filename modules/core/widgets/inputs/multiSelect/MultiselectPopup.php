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

    const ADDON_TYPE_APPEND = 'append';
    const ADDON_TYPE_PREPEND = 'prepend';

    /**
     * @var string
     */
    public $addonType = self::ADDON_TYPE_APPEND;

    public $addonGlyphiconClass;

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
    public $multiply = false;

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
            $value = $this->clientOptions['selectedValues'] ? $this->clientOptions['selectedValues'][$this->model->{$this->attribute}] : null;
            $html = Html::activeInput('text', $this->model, $this->attribute, [
                'class' => 'form-control input-sm',
                'value' => $value,
            ]);
            $html .= Html::tag('span', '<i class="glyphicon glyphicon-remove"></i>', ['class' => 'input-group-addon']);

            echo Html::tag('div', $html, ['class' => 'input-group']);
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

    /**
     * Renders the source input for the [[DateTimePicker]] plugin. Graceful fallback to a normal HTML  text input - in
     * case JQuery is not supported by the browser
     */
    protected function renderInput()
    {
        if ($this->type == self::TYPE_INLINE) {
            if (empty($this->options['readonly'])) {
                $this->options['readonly'] = true;
            }
            if (empty($this->options['class'])) {
                $this->options['class'] = 'form-control input-sm text-center';
            }
        } else {
            Html::addCssClass($this->options, 'form-control');
        }
        $input = $this->type == self::TYPE_BUTTON ? 'hiddenInput' : 'textInput';
        return $this->parseMarkup($this->getInput($input));
    }

    /**
     * Returns the addon for prepend or append.
     *
     * @param string|array $options the HTML attributes for the addon (if passed as an array) or the addon markup if
     * passed as a string
     * @param string       $type whether the addon is the picker or remove
     *
     * @return string
     */
    protected function renderAddon(&$options, $type = 'picker')
    {
        if ($options === false) {
            return '';
        }
        if (is_string($options)) {
            return $options;
        }
        Html::addCssClass($options, 'input-group-addon');
        $icon = ($type === 'picker') ? 'calendar' : 'remove';
        $icon = '<span class="glyphicon glyphicon-' . ArrayHelper::remove($options, 'icon', $icon) . '"></span>';
        if (empty($options['title'])) {
            $title = ($type === 'picker') ? Yii::t('kvdtime', 'Select date & time') : Yii::t('kvdtime', 'Clear field');
            if ($title != false) {
                $options['title'] = $title;
            }
        }
        return Html::tag('span', $icon, $options);
    }
}
