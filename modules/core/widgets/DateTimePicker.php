<?php

namespace app\modules\core\widgets;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
use lusin\backend\widgets\inputs\assets\DatePickerAsset;
use Yii;
use yii\base\InvalidConfigException;

class DateTimePicker extends \kartik\datetime\DateTimePicker
{
    /**
     * @inheritdoc
     */
    public $layout = '{input}{picker}{remove}';

    /**
     * @inheritdoc
     */
    public $convertFormat = true;
}
