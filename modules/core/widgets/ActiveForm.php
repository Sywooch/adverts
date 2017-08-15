<?php

namespace app\modules\core\widgets;

use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\widgets\ActiveFormAsset;

class ActiveForm extends \yii\bootstrap\ActiveForm
{
    /**
     * @inheritdoc
     */
    public $fieldClass = 'app\modules\core\widgets\ActiveField';

    /**
     * @var bool whether to send request via ajax
     */
    public $ajaxSubmit = false;

    /**
     * @var bool whether server to send redirect header after form submitting
     */
    public $sendAjaxRedirectHeader = true;

    /**
     * Widget client event handlers list.
     */
    public $clientEvents;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (Yii::$app->request->isAjax) {
            $this->ajaxSubmit = true;
        }
    }

    /**
     * @inheritdoc
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $options = Json::htmlEncode($this->getClientOptions());
        $attributes = Json::htmlEncode($this->attributes);
        $view = $this->getView();
        ActiveFormAsset::register($view);
        $view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
        $this->registerClientEvents();
    }

    /**
     * @inheritdoc
     */
    protected function getClientOptions()
    {
        $options = [
            'encodeErrorSummary' => $this->encodeErrorSummary,
            'errorSummary' => '.' . implode('.', preg_split('/\s+/', $this->errorSummaryCssClass, -1, PREG_SPLIT_NO_EMPTY)),
            'validateOnSubmit' => $this->validateOnSubmit,
            'errorCssClass' => $this->errorCssClass,
            'successCssClass' => $this->successCssClass,
            'validatingCssClass' => $this->validatingCssClass,
            'ajaxParam' => $this->ajaxParam,
            'ajaxDataType' => $this->ajaxDataType,
            'ajaxSubmit' => $this->ajaxSubmit,
            'sendAjaxRedirectHeader' => $this->sendAjaxRedirectHeader,
        ];
        if ($this->validationUrl !== null) {
            $options['validationUrl'] = Url::to($this->validationUrl);
        }
        // only get the options that are different from the default ones (set in yii.activeForm.js)
        return array_diff_assoc($options, [
            'encodeErrorSummary' => true,
            'errorSummary' => '.error-summary',
            'validateOnSubmit' => true,
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success',
            'validatingCssClass' => 'validating',
            'ajaxParam' => 'ajax',
            'ajaxDataType' => 'json',
            'ajaxSubmit' => false,
            'sendAjaxRedirectHeader' => true,
        ]);
    }

    /**
     * Registers a specific widget events.
     */
    protected function registerClientEvents()
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                if (is_array($handler)) {
                    foreach ($handler as $func) {
                        $js[] = "jQuery('#$this->id').on('$event', $func);";
                    }
                } else if ($handler) {
                    $js[] = "jQuery('#$this->id').on('$event', $handler);";
                }
            }
            $this->getView()->registerJs(implode("\n", $js));
        }
    }
}