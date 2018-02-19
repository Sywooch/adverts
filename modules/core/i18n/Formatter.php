<?php

namespace app\modules\core\i18n;

use app\modules\core\helpers\DateTimeHelper;
use app\modules\currency\models\ar\Currency;

class Formatter extends \yii\i18n\Formatter
{
    public function asCurrencyRange($valueFrom = null, $valueTo = null, $currency = null, $options = [], $textOptions = [])
    {
        $this->numberFormatterSymbols[\NumberFormatter::CURRENCY_SYMBOL] = Currency::getSignByCode($currency);

        if ($valueFrom && $valueTo) {
            if ($valueFrom == $valueTo) {
                return parent::asCurrency($valueFrom, $currency, $options, $textOptions);
            } else {
                $this->numberFormatterSymbols[\NumberFormatter::CURRENCY_SYMBOL] = '';
                $start = parent::asCurrency($valueFrom, $currency, $options, $textOptions);

                $this->numberFormatterSymbols[\NumberFormatter::CURRENCY_SYMBOL] = Currency::getSignByCode($currency);
                return "{$start} - " . parent::asCurrency($valueTo, $currency, $options, $textOptions);
            }
        } else if ($valueFrom && !$valueTo) {
            return 'от ' . parent::asCurrency($valueFrom, $currency, $options, $textOptions);
        } else if (!$valueFrom && $valueTo) {
            return 'до ' . parent::asCurrency($valueTo, $currency, $options, $textOptions);
        }
    }

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
