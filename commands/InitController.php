<?php

namespace app\commands;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertCategory;
use app\modules\core\models\ar\Comment;
use app\modules\currency\models\ar\Currency;
use app\modules\core\models\ar\Like;
use app\modules\core\models\ar\Look;
use app\modules\geography\models\ar\Geography;
use app\modules\users\models\ar\Profile;
use app\modules\users\models\ar\User;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class InitController
 * @package app\commands
 */
class InitController extends \app\modules\core\console\Controller
{
    /**
     * Loads initial data to DB.
     */
    public function actionIndex()
    {
        $this->loadCategories();
        $this->loadCurrencies();
        $this->loadGeography();

        Yii::$app->runAction('currency/load-rates');

        if (!YII_ENV_PROD) {
            /*$adverts = require Yii::getAlias('@app/data/db/adverts.php');
            foreach ($adverts as $advertData) {
                $advertData['user_id'] = 1;
                $comments = ArrayHelper::remove($advertData,'comments', []);
                $advert = new Advert(array_merge($advertData, ['status' => Advert::STATUS_ACTIVE]));
                if (!$advert->save()) {
                    print_r($advert->getErrors());
                }
                foreach ($comments as $commentData) {
                    $comment = new Comment(array_merge($commentData, [
                        'user_id' => 1, //rand(1, 3),
                        'owner_id' => $advert->id,
                        'owner_model_name' => $advert::shortClassName()
                    ]));
                    if (!$comment->save()) {
                        print_r($comment->getErrors());
                    }
                }
            }*/
        }
    }

    protected function loadCategories()
    {
        $categories = require Yii::getAlias('@app/data/db/categories.php');
        foreach ($categories as $categoryData) {
            $category = new AdvertCategory($categoryData);
            if (!$category->save()) {
                print_r($category->getErrors());
            }
        }
    }

    protected function loadCurrencies()
    {
        $currencies = require Yii::getAlias('@app/data/db/currencies.php');
        foreach ($currencies as $currencyData) {
            $currency = new Currency($currencyData);
            if (!$currency->save()) {
                print_r($currency->getErrors());
            }
        }
    }

    /**
     * Loads geography objects to the database.
     */
    protected function loadGeography()
    {
        /** @var VKontakte $client */
        $client = Yii::$app->get('authClientCollection')->getClient('vkontakte');
        $client->setAccessToken([
            'token' => VKONTAKTE_ACCESS_TOKEN
        ]);

        $regionsToBd = [];
        $citiesToBd = [];
        $regions = $client->post('database.getRegions', ['country_id' => 2, 'lang' => 'ru']);
        foreach ($regions['response'] as $regionData) {
            if (in_array($regionData['region_id'], ['1502709', '1506831'])) {
                $regionsToBd[] = [
                    'type' => Geography::TYPE_REGION,
                    'service_id' => $regionData['region_id'],
                    'title' => $regionData['title']
                ];

                // Cities
                $params = ['country_id' => 2, 'region_id' => $regionData['region_id'], 'lang' => 'ru', 'offset' => 0];
                do {
                    $cities = $client->post('database.getCities', $params);

                    foreach ($cities['response'] as $cityData) {
                        $citiesToBd[] = [
                            'type' => Geography::TYPE_CITY,
                            'service_id' => $cityData['cid'],
                            'title' => $cityData['title'],
                            'parent_id' => $regionData['region_id'],
                        ];
                    }

                    $params['offset'] += 100;
                } while (count($cities['response']));
            }
        }

        Yii::$app->db->createCommand()->batchInsert(Geography::tableName(), [
            'type', 'service_id', 'title'
        ], $regionsToBd)->execute();

        Yii::$app->db->createCommand()->batchInsert(Geography::tableName(), [
            'type', 'service_id', 'title', 'parent_id'
        ], $citiesToBd)->execute();
    }

    /**
     * Loads test data to DB.
     */
    public function actionTestData()
    {
        $usersCount = 1000;
        $advertsCount = 10000;

        // Users
        echo "Создаю пользователей...\n";

        $security = Yii::$app->security;
        $users = [];
        $profiles = [];
        for ($i = 2; $i <= $usersCount ; $i++) {
            $users[] = [
                $security->generateRandomString(rand(4, 24)) . '@mail.ru',
                $security->generateRandomString(rand(4, 32)),
                User::STATUS_ACTIVE,
                $security->generateRandomString(32),
            ];
            $profiles[] = [
                $i,
                $security->generateRandomString(rand(2, 8)),
                $security->generateRandomString(rand(4, 12)),
            ];
            if ($i % 100 == 0) {
                echo "\t$i\n";
            }
        }

        echo "Загружаю пользователей в БД... ";

        Yii::$app->db->createCommand()->batchInsert(User::tableName(), [
            'email', 'password', 'status', 'auth_key'
        ], $users)->execute();
        Yii::$app->db->createCommand()->batchInsert(Profile::tableName(), [
            'user_id', 'first_name', 'last_name'
        ], $profiles)->execute();
        unset($profiles, $users);

        echo "загрузил.\n";
        echo "Создаю объявления... создано:\n";

        $geographyCount = Geography::find()->count();
        $currencyCount = Currency::find()->count();
        $categoryCount = AdvertCategory::find()->count();
        // Adverts
        $adverts = [];
        for ($i = 2; $i <= $advertsCount; $i++) {
            $text = '';
            $words = rand(10, 200);
            for ($w = 0; $w < $words; $w++) {
                $text .= ' ' . $security->generateRandomString(rand(2, 10));
            };
            $rand = rand(1,10);
            if ($rand % 2 == 0) {
                $minPrice = rand(0, 1000);
            }
            $rand = rand(1,10);
            if ($rand % 2 == 0) {
                $maxPrice = $minPrice + rand(0, 10000);
            }
            $createdAt = rand(time() - 3600 * 24 * 365, time());
            $expiryAt = rand($createdAt, rand(0, 3600 * 24 * 60));
            $adverts[] = [
                rand(1, $usersCount), $text, Advert::STATUS_ACTIVE, $minPrice, $maxPrice,
                rand(1, $geographyCount), rand(1, $categoryCount), rand(1, $currencyCount),
                date('Y:m:d H:i:s', $createdAt), date('Y:m:d H:i:s', $expiryAt)
            ];

            if ($i % 1000 == 0) {
                echo "{$i} ";
            }
            if ($i % 10000 == 0) {
                echo "Загружаю {$i} объявления в БД... ";
                Yii::$app->db->createCommand()->batchInsert(Advert::tableName(), [
                    'user_id', 'content', 'status', 'min_price', 'max_price',
                    'geography_id', 'category_id', 'currency_id',
                    'created_at', 'expiry_at'
                ], $adverts)->execute();
                unset($adverts);
                $adverts = [];
                echo "загрузил.\n";
            }
        }
        unset($adverts);

        echo "Создаю татистику для объявлений...\n";

        $likes = [];
        $looks = [];
        $bookmarks = [];
        $comments = [];
        for ($advertId = 1; $advertId <= $advertsCount ; $advertId++) {
            for ($uId = 1; $uId <= 10; $uId++) {
                $likes[] = [rand(1, $usersCount), $advertId, Advert::shortClassName(), Advert::LIKE_VALUE];
                $likes[] = [rand(1, $usersCount), $advertId, Advert::shortClassName(), Advert::DISLIKE_VALUE];
                $looks[] = [rand(1, $usersCount), $advertId, Advert::shortClassName(), rand(1, 10)];
                $bookmarks[] = [rand(1, $usersCount), $advertId, Advert::shortClassName()];
                $text = '';
                $words = rand(1, 20);
                for ($w = 0; $w < $words; $w++) {
                    $text .= ' ' . $security->generateRandomString(rand(2, 10));
                };
                $comments[] = [rand(1, $usersCount), $advertId, Advert::shortClassName(), $text];
            }
            if ($advertId % 5000 == 0) {
                echo "Загружаю статистику объявлений в БД... ";

                Yii::$app->db->createCommand()->batchInsert(Like::tableName(), [
                    'user_id', 'owner_id', 'owner_model_name', 'value'
                ], $likes)->execute();
                Yii::$app->db->createCommand()->batchInsert(Look::tableName(), [
                    'user_id', 'owner_id', 'owner_model_name', 'value'
                ], $looks)->execute();
                Yii::$app->db->createCommand()->batchInsert(Look::tableName(), [
                    'user_id', 'owner_id', 'owner_model_name'
                ], $bookmarks)->execute();
                Yii::$app->db->createCommand()->batchInsert(Comment::tableName(), [
                    'user_id', 'owner_id', 'owner_model_name', 'text'
                ], $comments)->execute();
                unset($likes, $looks);
                echo "загрузил для {$i}.\n";
            }
        }
    }
}