<?php

namespace app\modules\geography\models\search;

use app\modules\geography\models\ar\Geography;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class GeographySearch extends Geography
{
    /**
     * @var integer
     */
    public $pageSize;

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
            'name' => Yii::t('app', 'Название'),
        ];
    }

    /**
     * Creating model search query.
     * @return ActiveDataProvider|\yii\data\DataProviderInterface
     */
    public function search($params = [])
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'asc' => ['name']
                ]
            ]
        ]);

        if ($params && !($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return self::find()->select(['id', 'name'])
            ->orderBy('name')
            ->asArray()
            ->all();
    }

    /**
     * @return array
     */
    public static function getCityListGropedByRegion()
    {
        $cities = self::find()
            ->alias('city')
            ->select([
                'id' => 'city.id',
                'title' => 'city.title',
                'region_title' => 'region.title',
            ])
            ->innerJoin(['region' => self::tableName()], [
                'region.type' => Geography::TYPE_REGION,
                'region.service_id' => new Expression('city.parent_id')
            ])
            ->where([
                'city.type' => Geography::TYPE_CITY
            ])
            ->orderBy(['region.title' => SORT_ASC, 'city.title' => SORT_ASC])
            ->asArray()
            ->all();
        $return = [];
        foreach ($cities as $city) {
            if (!isset($return[$city['region_title']])) {
                $return[$city['region_title']] = [];
            }
            $return[$city['region_title']][$city['id']] = $city['title'];
        }
        return $return;
    }
}