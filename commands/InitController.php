<?php

namespace app\commands;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertCategory;
use app\modules\core\models\ar\Comment;
use app\modules\core\models\ar\Currency;
use app\modules\core\models\ar\Like;
use app\modules\core\models\ar\Look;
use app\modules\geography\models\ar\Geography;
use app\modules\users\models\ar\Profile;
use app\modules\users\models\ar\User;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class InitController
 * @package app\commands
 */
class InitController extends \app\modules\core\console\Controller
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
            $advertData['user_id'] = rand(1, 3);
            $comments = ArrayHelper::remove($advertData,'comments', []);
            $advert = new Advert(array_merge($advertData, ['status' => Advert::STATUS_ACTIVE]));
            if (!$advert->save()) {
                print_r($advert->getErrors());
            }
            foreach ($comments as $commentData) {
                $comment = new Comment(array_merge($commentData, [
                    'user_id' => rand(1, 3),
                    'owner_id' => $advert->id,
                    'owner_model_name' => $advert::shortClassName()
                ]));
                if (!$comment->save()) {
                    print_r($comment->getErrors());
                }
            }
        }
    }

    public function actionTest()
    {
        $usersCount = 1000;
        $advertsCount = 10000;

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
            if ($i % 1000 == 0) {
                echo "User №$i\n";
            }
        }
        Yii::$app->db->createCommand()->batchInsert(User::tableName(), [
            'email', 'password', 'status', 'auth_key'
        ], $users)->execute();
        Yii::$app->db->createCommand()->batchInsert(Profile::tableName(), [
            'user_id', 'first_name', 'last_name'
        ], $profiles)->execute();
        unset($profiles, $users);

        $adverts = [];
        for ($i = 2; $i <= $advertsCount; $i++) {
            $text = '';
            $words = rand(10, 200);
            for ($w = 0; $w < $words; $w++) {
                $text .= ' ' . $security->generateRandomString(rand(2, 10));
            };
            $rand = rand(1,10);
            if ($rand % 2 == 0) {
                $minPrice = rand(100, 1000);
            }
            $rand = rand(1,10);
            if ($rand % 2 == 0) {
                $maxPrice = rand(1000, 100000);
            }
            $adverts[] = [rand(1, $usersCount), $text, Advert::STATUS_ACTIVE, $minPrice, $maxPrice];
            if ($i % 1000 == 0) {
                echo "Advert №$i\n";
            }
            if ($i % 10000 == 0) {
                Yii::$app->db->createCommand()->batchInsert(Advert::tableName(), [
                    'user_id', 'content', 'status', 'min_price', 'max_price'
                ], $adverts)->execute();
                unset($adverts);
                $adverts = [];
            }
        }
        unset($adverts);


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
                echo "Like  №$advertId\n";
                unset($likes, $looks);
            }
        }
    }

    /**
     * Loads geography objects to the database.
     */
    public function actionGeography()
    {
        $user = User::find()->with('authClientUser')->where([
            'id' => 2
        ])->one();

        /** @var VKontakte $clientVk */
        $clientVk = Yii::$app->authClientCollection->getClient('vkontakte');
        $clientVk->setAccessToken(['token' => $user->authClientUser->access_token]);

        $regionsToBd = [];
        $citiesToBd = [];
        $regions = $clientVk->post('database.getRegions', [
            'country_id' => 2,
        ]);
        foreach ($regions['response'] as $regionData) {
            if (in_array($regionData['region_id'], ['1502709', '1506831'])) {
                $regionsToBd[] = [
                    'type' => Geography::TYPE_REGION,
                    'service_id' => $regionData['region_id'],
                    'title' => $regionData['title']
                ];
                $cities = $clientVk->post('database.getCities', [
                    'country_id' => 2,
                    'region_id' => $regionData['region_id']
                ]);
                foreach ($cities['response'] as $cityData) {
                    $citiesToBd[] = [
                        'type' => Geography::TYPE_CITY,
                        'service_id' => $cityData['cid'],
                        'title' => $cityData['title'],
                        'parent_id' => $regionData['region_id'],
                    ];
                }
            }
        }

        Yii::$app->db->createCommand()->batchInsert(Geography::tableName(), [
            'type', 'service_id', 'title'
        ], $regionsToBd)->execute();

        Yii::$app->db->createCommand()->batchInsert(Geography::tableName(), [
            'type', 'service_id', 'title', 'parent_id'
        ], $citiesToBd)->execute();
    }
}