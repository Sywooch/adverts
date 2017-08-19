<?php

namespace app\modules\core\web;

class View extends \yii\web\View
{
    /**
     * @inheritdoc
     */
    public function renderFile($viewFile, $params = [], $context = null)
    {
        if (!is_file($viewFile)) {
            $viewFile = str_replace(['\\back\\', '/back/'], ['\\front\\', '/front/'], $viewFile);
        }
        return parent::renderFile($viewFile, $params, $context);
    }
}