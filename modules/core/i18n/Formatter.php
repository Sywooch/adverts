<?php

namespace app\modules\core\i18n;

use app\modules\core\helpers\DateTimeHelper;

class Formatter extends \yii\i18n\Formatter
{
    /**
     * @inheritdoc
     */
    public function asDatetime($value, $format = null)
    {
        return DateTimeHelper::convertNamesFromSystem(parent::asDatetime($value, $format));
    }

    /**
     * @inheritdoc
     */
    public function asDate($value, $format = null)
    {
        return DateTimeHelper::convertNamesFromSystem(parent::asDate($value, $format));
    }
}
