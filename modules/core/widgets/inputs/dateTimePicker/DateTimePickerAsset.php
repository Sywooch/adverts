<?php

namespace app\modules\core\widgets\inputs\dateTimePicker;

use kartik\base\AssetBundle;

class DateTimePickerAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setSourcePath(__DIR__ . '/src');
        $this->setupAssets('css', ['css/bootstrap-datetimepicker', 'css/datetimepicker-kv']);
        $this->setupAssets('js', ['js/bootstrap-datetimepicker']);

        parent::init();
    }
}