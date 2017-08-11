<?php

namespace app\modules\adverts\models\search;

use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertCategory;
use Yii;
use app\modules\core\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class AdvertSearch extends Advert
{
    /**
     * @var integer
     */
    public $pageSize;

    /**
     * @var
     */
    public $min_date;

    /**
     * @var
     */
    public $max_date;

    /**
     * @var string
     */
    public $phrase;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['pageSize', 'integer', 'integerOnly' => true, 'min' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'max_date' => Yii::t('app', 'Минимальная дата'),
            'min_date' => Yii::t('app', 'Максимальная дата'),
            'phrase' => Yii::t('app', 'Фраза'),
        ]);
    }

    /**
     * Creating model search query.
     * @param array $params
     * @return ActiveDataProvider|\yii\data\DataProviderInterface
     */
    public function search($params = [])
    {
        $query = Advert::find()
            ->active()
            ->withCommentsCount()
            ->withDislikesCount()
            ->withLikesCount()
            ->withLooksCount()
            ->withBookmarksCurrentUser()
            ->withLikesCurrentUser()
            ->with(['user.profile.authClientUser', 'category', 'files', 'geography', 'currency'])
            ->groupBy(['advert.id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                //'pageSize' => \roman444uk\yii\widgets\WidgetPageSize::getPageSize(),
                'pageSize' => 5
            ],
            'sort' => [
                'attributes' => [
                    'asc' => [
                        'created_at', 'min_price', 'max_price'
                    ]
                ]
            ]
        ]);

        if ($params && !($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->buildQuery($query, $params);

        return $dataProvider;
    }

    /**
     * Returns all published adverts.
     * @return ActiveDataProvider|\yii\data\DataProviderInterface
     */
    public function searchPublished()
    {
        $dataProvider = $this->search();
        $dataProvider->query->published();
        return $dataProvider;
    }

    /**
     * Returns all bookmarked adverts.
     * @return ActiveDataProvider|\yii\data\DataProviderInterface
     */
    public function searchBookmarked()
    {
        $dataProvider = $this->search();
        $dataProvider->query->bookmarked();
        return $dataProvider;
    }

    /**
     *
     * @param type $query
     * @param type $params
     */
    public function buildQuery($query, $params)
    {
        $tableAdvert = self::tableName();

        if ($this->category_id) {
            $query->andWhere("{$tableAdvert}.category_id = :category", [':category' => $this->category_id]);
        }

        /*if (!empty($this->phrase)) {
            $ids = [];
            foreach ((new \yii\sphinx\Query)->from(self::tableName())->match($this->phrase)->all() as $row) {
                array_push($ids, $row['id']);
            }
            $query->andWhere(["{$tableAdvert}.id" => $ids]);
        }*/


        if (!empty($this->min_date)) {
            $query->andWhere("{$tableAdvert}.created_at >= :minDate", [':minDate' => $this->min_date]);
        }

        if (!empty($this->max_date)) {
            $query->andWhere("{$tableAdvert}.created_at >= :maxDate", [':minDate' => $this->max_date]);
        }

        if (!empty($this->min_price)) {
            $query->andWhere("{$tableAdvert}.min_price = :minPrice", [':minprice' => $this->min_price]);
        }

        if (!empty($this->max_price)) {
            $query->andWhere("{$tableAdvert}.max_price = :maxPrice", [':maxPrice' => $this->max_price]);
        }

        $query->orderBy(self::tableName() . '.created_at desc');
    }
}