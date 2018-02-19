<?php

namespace app\modules\adverts\models\search;

use app\modules\adverts\data\AdvertSort;
use app\modules\adverts\models\ar\Advert;
use app\modules\adverts\models\ar\AdvertCategory;
use app\modules\core\behaviors\ar\DateTimeBehavior;
use app\modules\core\data\ActiveDataProvider;
use app\modules\core\db\ActiveQuery;
use app\modules\currency\components\CurrencyHelper;
use app\modules\currency\models\ar\Currency;
use app\modules\currency\models\ar\CurrencyRate;
use app\modules\geography\models\ar\Geography;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use app\modules\core\widgets\WidgetPageSize;
use app\modules\adverts\AdvertsModule;

/**
 * @property boolean $active
 * @property int $ui_currency_id
 * @property string $phrase
 * @property string $min_date
 * @property string $max_date
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
            [['pageSize', 'currency_id', 'ui_currency_id'], 'number', 'integerOnly' => true],
            [['min_price', 'max_price'], 'number'],
            [['min_price', 'max_price'], 'validatePrice'],
            [['min_date', 'max_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['phrase', 'category_id', 'geography_id'], 'safe']
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(), [
            'phrase', 'min_date', 'max_date', 'ui_currency_id'
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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->currency_id = Currency::find()->select('id')->where(['code' => Currency::RUB])->scalar();
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        /*if (!empty($data['category_id']) && !is_array($data['category_id'])) {
            $data['category_id'] = explode(',', $data['category_id']);
        }
        if (!empty($data['geography_id']) && !is_array($data['geography_id'])) {
            $data['geography_id'] = explode(',', $data['geography_id']);
        }*/

        return parent::load($data, $formName);
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
        $this->buildSelect($query);

        $tableAdvert = self::tableName();

        if (!empty($this->phrase)) {
            $ids = [];
            foreach ((new \yii\sphinx\Query)->from(self::tableName())->match($this->phrase)->all() as $row) {
                array_push($ids, $row['id']);
            }
            $query->andWhere(["{$tableAdvert}.id" => $ids]);
        }

        if ($this->status) {
            $query->andWhere(['in', "{$tableAdvert}.status", $this->status]);
        }

        if (!empty($this->geography_id)) {
            $query->innerJoin(['child' => Geography::tableName()], "child.id = {$tableAdvert}.geography_id")
                ->innerJoin(['parent' => Geography::tableName()], "parent.id = child.parent_id")
                ->andWhere(['or',
                    ['in', "{$tableAdvert}.geography_id", $this->geography_id],
                    ['in', 'child.parent_id', $this->geography_id]
                ]);
        }

        if (!empty($this->category_id)) {
            $query->andWhere(['in', "{$tableAdvert}.category_id", $this->category_id]);
        }

        if (!empty($this->min_date)) {
            $query->andWhere("{$tableAdvert}.created_at >= :minDate", [':minDate' => $this->min_date]);
        }

        if (!empty($this->max_date)) {
            $query->andWhere("{$tableAdvert}.created_at <= :maxDate", [':maxDate' => $this->max_date]);
        }

        if ($this->currency_id && ($this->min_price || $this->max_price)) {
            $query->leftJoin(['comp_rate' => CurrencyRate::tableName()], "comp_rate.src_id = advert.currency_id AND comp_rate.dst_id = :currency_id", [
                'currency_id' => (int) $this->currency_id
            ]);
        }

        if (!empty($this->min_price)) {
            $query->andWhere("{$tableAdvert}.min_price * comp_rate.value >= :minPrice OR {$tableAdvert}.max_price * comp_rate.value >= :minPrice", [
                ':minPrice' => (float) $this->min_price
            ]);
        }

        if (!empty($this->max_price)) {
            $query->andWhere("{$tableAdvert}.max_price * comp_rate.value <= :maxPrice OR {$tableAdvert}.min_price * comp_rate.value <= :maxPrice", [
                ':maxPrice' => (float) $this->max_price
            ]);
        }
    }

    /**
     * @param ActiveQuery $query
     */
    protected function buildSelect($query)
    {
        // Select conditions for displaying result in different currencies
        if ($this->ui_currency_id) {
            $attributes = array_flip(parent::realAttributes());
            unset($attributes['currency_id'], $attributes['min_price'], $attributes['max_price']);
            $attributes = array_flip($attributes);
            $attributes = array_map(function ($column) {return "advert.{$column}";}, $attributes);

            $attributes['currency_id'] = new Expression($this->ui_currency_id);
            $attributes['min_price'] = new Expression("ROUND(`min_price` * `rate`.`value`, 2)", [
                'ui_currency_id' => (int) $this->ui_currency_id
            ]);
            $attributes['max_price'] = new Expression("ROUND(`max_price` * `rate`.`value`, 2)", [
                'ui_currency_id' => (int) $this->ui_currency_id
            ]);

            $query
                ->select($attributes)
                ->leftJoin(['rate' => CurrencyRate::tableName()], "`rate`.`src_id` = `advert`.`currency_id` AND `rate`.`dst_id` = :ui_currency_id", [
                    'ui_currency_id' => (int) $this->ui_currency_id
                ])
                ->addGroupBy('`rate`.`id`');
        }
    }

    /**
     * @param string $attribute
     */
    public function validatePrice($attribute)
    {
        if ($this->min_price && $this->max_price && $this->min_price > $this->max_price) {
            $this->addError($attribute, AdvertsModule::t('Минимальная цена должна быть меньше максимальной'));
        }
    }
}