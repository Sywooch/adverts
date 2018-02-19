<?php

namespace app\modules\currency\components;

use app\modules\currency\models\ar\CurrencyRate;

class CurrencyHelper
{
    /**
     * @var CurrencyRate[]
     */
    protected static $currenciesRates;

    /**
     * @param float $price
     * @param string $currencyFrom
     * @param string $currencyTo
     * @return float
     */
    public static function recalculatePrice($price, $currencyFrom, $currencyTo)
    {
        if ($currencyFrom != $currencyTo) {
            $price *= self::getRate($currencyFrom, $currencyTo);
        }

        return $price;
    }

    /**
     * @param string $currencyFrom
     * @param string $currencyTo
     * @return float
     */
    protected static function getRate($currencyFrom, $currencyTo)
    {
        if (self::$currenciesRates === null) {
            self::$currenciesRates = CurrencyRate::find()->with('currency')->indexBy(function($model) {
                /** @var CurrencyRate $model */
                return $model->dstCurrency->code . $model->srcCurrency->code;
            })->all();
        }

        return self::$currenciesRates[$currencyFrom . $currencyTo];
    }

    public function getRates()
    {
        $rates = CurrencyRate::find()->with('currency')->all();
        $return = [];

        /** @var CurrencyRate $rate */
        foreach ($rates as $rate) {
            if (!isset($return[$rate->srcCurrency->code])) {

            }
        }
    }
}