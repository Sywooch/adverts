<?php

namespace app\modules\core\widgets\inputs\multiSelect;

use yii\web\AssetBundle;

class MultiselectPopupAsset extends AssetBundle
{
    public $i18n;
    /**
     * @inheritdoc
     */
    //public $sourcePath = '@app/modules/core/widgets/inputs/multiSelect/src';
    public $baseUrl = '@web/src';

    /**
     * @inheritdoc
     */
    public $js = [
        'jquery.multiselectPopup.js'
    ];
    /**
     * @inheritdoc
     */
    public $css = [
        'multiselectPopup.css'
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\jui\JuiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public function init()
    {
        $this->js[] = 'i18n/' . \Yii::$app->language . '.js';
        parent::init();
    }
}
