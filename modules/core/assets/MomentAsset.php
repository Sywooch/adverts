<?php

namespace app\modules\core\assets;

use yii\web\AssetBundle;

class MomentAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/moment/moment/src';

    /**
     * @inheritdoc
     */
    public $baseUrl = '@web';

    /**
     * @inheritdoc
     */
    public $css = [];

    /**
     * @inheritdoc
     */
    public $js = [
        'moment.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}