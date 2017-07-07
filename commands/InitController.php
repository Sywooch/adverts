<?php

namespace app\commands;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertCategory;
use app\modules\currencies\models\ar\Currency;
use yii\console\Controller;
use Yii;

/**
 * Class InitController
 * @package app\commands
 */
class InitController extends Controller
{
    /**
     *
     */
    public function actionIndex()
    {
        $categories = require Yii::getAlias('@app/data/db/categories.php');
        foreach ($categories as $categoryData) {
            $category = new AdvertCategory($categoryData);
            if (!$category->save()) {
                print_r($category->getErrors());
            }
        }

        $currencies = require Yii::getAlias('@app/data/db/currencies.php');
        foreach ($currencies as $currencyData) {
            $currency = new Currency($currencyData);
            if (!$currency->save()) {
                print_r($currency->getErrors());
            }
        }

        $adverts = require Yii::getAlias('@app/data/db/adverts.php');
        foreach ($adverts as $advertData) {
            $advertData['user_id'] = 1;
            $advert = new Advert($advertData);
            if (!$advert->save()) {
                print_r($advert->getErrors());
            }
        }
    }
}