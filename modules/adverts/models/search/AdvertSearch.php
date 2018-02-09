<?php

namespace app\modules\adverts\models\search;

use app\modules\adverts\data\AdvertSort;
use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertCategory;
use app\modules\core\behaviors\ar\DateTimeBehavior;
use app\modules\core\data\ActiveDataProvider;
use app\modules\core\db\ActiveQuery;
use Yii;
use yii\helpers\ArrayHelper;
use app\modules\core\widgets\WidgetPageSize;

/**
 * @property boolean $active
 */
class AdvertSearch extends Advert
{
    /**
     * @var integer
     */
    public $pageSize;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'datetime' => [
                'class' => DateTimeBehavior::className(),
                'datetimeAttributes' => ['min_date', 'max_date'],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pageSize', 'currency_id', 'geography_id', 'category_id'], 'number', 'integerOnly' => true],
            [['min_price', 'max_price'], 'number'],
            [['min_date', 'max_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['phrase'], 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'phrase', 'min_date', 'max_date'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'max_date' => Yii::t('app', 'Максимальная дата'),
            'min_date' => Yii::t('app', 'Минимальная дата'),
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
            ->withCommentsCount()
            ->withDislikesCount()
            ->withLikesCount()
            ->withLooksCount()
            ->withBookmarksCurrentUser()
            ->withLikesCurrentUser()
            ->with(['user.profile.userAuthClient', 'category', 'files', 'geography', 'currency'])
            ->groupBy(['advert.id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => WidgetPageSize::getPageSize('adverts-list'),
                'pageSize' => WidgetPageSize::getPageSize('adverts-list'),
            ],
            'sort' => new AdvertSort(),
        ]);

        if ($params && !($this->load($params) && $this->validate())) {
            $query->andWhere('1 = 0');
            return $dataProvider;
        }

        $this->buildQuery($query, $params);

        return $dataProvider;
    }

    /**
     * Returns all active adverts.
     * @param array $params
     * @return ActiveDataProvider|\yii\data\DataProviderInterface
     */
    public function searchActive($params = [])
    {
        $dataProvider = $this->search($params);
        $dataProvider->query->active();
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
     * @param ActiveQuery $query
     * @param array $params
     */
    public function buildQuery($query, $params)
    {
        $tableAdvert = self::tableName();

        if ($this->status) {
            $query->andWhere(['in', "{$tableAdvert}.status", $this->status]);
        }

        if ($this->category_id) {
            $query->andWhere("{$tableAdvert}.category_id = :category", [':category' => $this->category_id]);
        }

        if (!empty($this->phrase)) {
            $ids = [];
            foreach ((new \yii\sphinx\Query)->from(self::tableName())->match($this->phrase)->all() as $row) {
                array_push($ids, $row['id']);
            }
            $query->andWhere(["{$tableAdvert}.id" => $ids]);
        }


        if (!empty($this->min_date)) {
            $query->andWhere("{$tableAdvert}.created_at >= :minDate", [':minDate' => $this->min_date]);
        }

        if (!empty($this->max_date)) {
            $query->andWhere("{$tableAdvert}.created_at <= :maxDate", [':maxDate' => $this->max_date]);
        }

        if (!empty($this->min_price)) {
            $query->andWhere("{$tableAdvert}.min_price >= :minPrice OR min_price IS NULL", [':minPrice' => $this->min_price]);
        }

        if (!empty($this->max_price)) {
            $query->andWhere("{$tableAdvert}.max_price <= :maxPrice OR max_price IS NULL", [':maxPrice' => $this->max_price]);
        }
    }
}