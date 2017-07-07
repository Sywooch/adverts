<?php

namespace app\modules\adverts\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\adverts\models\ar\Advert;

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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['pageSize', 'integer', 'integerOnly' => true, 'min' => 1],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'category' => Yii::t('app', 'Category'),
            'city_id' => Yii::t('app', 'City'),
            'content' => Yii::t('app', 'Content'),
            'created_at' => Yii::t('app', 'Created'),
            'price' => Yii::t('app', 'Price'),
            'max_price' => Yii::t('app', 'Price to'),
            'max_date' => Yii::t('app', 'Date to'),
            'min_price' => Yii::t('app', 'Price from'),
            'min_date' => Yii::t('app', 'Date from'),
            'published' => Yii::t('app', 'Is Published'),
            'status' => Yii::t('app', 'Status'),
            'term' => Yii::t('app', 'Term'),
            'type' => Yii::t('app', 'Type'),
            'updated_at' => Yii::t('app', 'Updated'),
        ];
    }

    /**
     * Creating model search query.
     * @return ActiveDataProvider|\yii\data\DataProviderInterface
     */
    public function search($params = [])
    {
        $query = $query = self::find()->with('owner.profile');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            /*'pagination' => [
                'pageSize' => \roman444uk\yii\widgets\WidgetPageSize::getPageSize(),
            ],*/
            'sort' => [
                'attributes' => [
                    'asc' => ['updated_at', 'updated_at', 'min_price', 'max_price']
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

        if (!empty($this->category)) {
            $query->andWhere('category = :category', [':category' => $this->category]);
        }

        /*if (!empty($this->content)) {
            $ids = [];
            foreach ((new \yii\sphinx\Query)->from(self::tableName())->match($this->content)->all() as $row) {
                array_push($ids, $row['id']);
            }
            $query->andWhere([self::tableName() . '.id' => $ids]);
        }*/


        if (!empty($this->min_date) && ($minDate = DatepickerHelper::convertDateFrom($this->min_date))) {
            $query->andWhere(self::tableName() . '.created_at >= :minDate', [':minDate' => $minDate]);
        }

        if (!empty($this->min_price)) {
            $query->andWhere('min_price = :min_price', [':min_price' => $this->min_price]);
        }

        if (!empty($this->max_price)) {
            $query->andWhere('max_price = :max_price', [':max_price' => $this->max_price]);
        }

        if (!empty($this->max_date) && ($maxDate = DatepickerHelper::convertDateTo($this->max_date))) {
            $query->andWhere(self::tableName() . '.created_at <= :maxDate', [':maxDate' => $maxDate]);
        }

        if (!empty($this->published) || $this->published === 0) {
            $query->andWhere('published = :published', [':published' => $this->published]);
        }

        if (!empty($this->user_id)) {
            $query->andWhere("$tableAdvert.user_id = :user_id", [':user_id' => $this->user_id]);
        }

        $query->orderBy(self::tableName() . '.created_at desc');
    }
}