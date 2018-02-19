<?php

namespace app\commands;

use app\modules\currency\models\ar\Currency;
use app\modules\currency\models\ar\CurrencyRate;
use app\modules\currency\models\search\CurrencySearch;
use yii\helpers\Json;

class CurrencyController extends \app\modules\core\console\Controller
{
    /**
     * Fetches actual currency rates.
     */
    public function actionLoadRates()
    {
        echo "Скачиваю курсы валют...\n";
        $currencies = Currency::find()->all();

        /** @var Currency $currency */
        foreach ($currencies as $currency) {
            $dstCurrencies = Currency::find()->all();

            /** @var Currency $dstCurrency */
            foreach ($dstCurrencies as $dstCurrency) {
                echo "\t{$currency->code}/{$dstCurrency->code} ";

                // Determining of rate value depending on the currencies.
                if ($currency->code != $dstCurrency->code) {
                    $url = "https://www.bloomberg.com/markets/api/security/currency/cross-rates/{$currency->code},{$dstCurrency->code}";
                    $data = file_get_contents($url);
                    $data = Json::decode($data);
                    $value = $data['data'][$currency->code][$dstCurrency->code];
                } else {
                    $value = 1;
                }

                // Try to find existing rate and assign value.
                $conditions = ['src_id' => $currency->id, 'dst_id' => $dstCurrency->id];
                if (!$rate = CurrencyRate::findOne($conditions)) {
                    $rate = new CurrencyRate($conditions);
                }
                $rate->value = $value;
                echo $rate->value;

                if (!$rate->save()) {
                    foreach ($rate->getFirstErrors() as $attribute => $error) {
                        echo "\t\t{$attribute}: {$error}\n";
                    }
                }

                echo "\n";
            }
        }
    }
}