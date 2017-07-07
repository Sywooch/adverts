<?php

namespace app\modules\core\widgets;

use yii\web\AssetBundle;

class ActiveFormAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@app/modules/core/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/yii.activeForm.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}