<?php

namespace app\assets;

use yii\web\AssetBundle;

class FrontEndAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $basePath = '@webroot';

    /**
     * @inheritdoc
     */
    public $baseUrl = '@web';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/front.css',
    ];

    /**
     * @inheritdoc
     */
    public $js = [

    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'app\assets\AppAsset',
    ];
}
