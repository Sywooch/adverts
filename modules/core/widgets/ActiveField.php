<?php

namespace app\modules\core\widgets;

use yii\helpers\ArrayHelper;

class ActiveField extends \yii\bootstrap\ActiveField
{
    /**
     * @inheritdoc
     */
    public function dropDownList($items, $options = [])
    {
        if ($emptyItem = ArrayHelper::remove($options, 'emptyItem', null)) {
            array_unshift($items, $emptyItem);
        }

        return parent::dropDownList($items, $options);
    }
}