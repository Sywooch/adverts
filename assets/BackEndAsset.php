<?php

namespace app\assets;

use yii\web\AssetBundle;

class BackEndAsset extends AssetBundle
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
        'css/back.css',
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
        'app\assets\AdminLteAsset',
        'app\assets\AppAsset',
    ];
}
