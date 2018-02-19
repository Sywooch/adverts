<?php

namespace app\modules\currency\widgets;

use app\modules\currency\models\ar\Currency;
use app\modules\currency\models\search\CurrencySearch;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class UiCurrency extends Widget
{
    /**
     * @var string
     */
    public $currencyParam = 'ui_currency_id';

    /**
     * @inheritdoc
     */
    public function run()
    {
        $links = [];

        foreach (CurrencySearch::getList() as $currency) {
            $links[] = $this->getLink(Currency::getFullPrepositionalName($currency['code']), $currency['id']);
        }

        $links[] = $this->getLink('указанной валюте');

        $content = 'Отображать цену в: ' . implode(', ', $links);
        return Html::tag('div', $content, [
            'class' => 'ui-currency'
        ]);
    }


    protected function getLink($text, $currencyId = null)
    {
        $request = Yii::$app->getRequest();
        $params = $request->getQueryParams();

        $isActive = $params[$this->currencyParam] == $currencyId ? true : false;

        if ($currencyId) {
            $params[$this->currencyParam] = $currencyId;
        } else {
            unset($params[$this->currencyParam]);
        }

        if ($isActive) {
            return Html::tag('span', $text, [
                'class' => 'active'
            ]);
        } else {
            return Html::a($text, Yii::$app->getUrlManager()->createUrl($params));
        }
    }

    /**
     * @param int|null $currencyId
     * @return string
     */
    protected function getUrl($currencyId = null)
    {
    }
}