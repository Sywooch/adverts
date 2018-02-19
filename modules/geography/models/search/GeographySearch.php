<?php

namespace app\modules\geography\models\search;

use app\modules\geography\models\ar\Geography;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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
     * @param $conditions
     * @param array $options
     * @return array
     */
    public static function getList($conditions = [], $options = [])
    {
        $query = self::find();

        if (isset($options['select'])) {
            $query->select($options['select']);
        }

        if (isset($options['order'])) {
            $query->orderBy($options['order']);
        } else {
            $query->orderBy('title');
        }

        return $query->andWhere($conditions)->asArray()->all();
    }

    /**
     * @return array
     */
    public static function getCityListGroupedByRegion()
    {
        $entities = self::find()
            ->alias('entity')
            ->select([
                'id' => 'entity.service_id',
                'title' => 'entity.title',
                'type' => 'entity.type',
                'parent_id' => 'parent.service_id',
                'parent_title' => 'parent.title',
            ])
            ->leftJoin(['parent' => self::tableName()], [
                'parent.type' => Geography::TYPE_REGION,
                'parent.service_id' => new Expression('entity.parent_id')
            ])
            ->orderBy(['parent.title' => SORT_ASC, 'entity.title' => SORT_ASC])
            ->asArray()
            ->all();

        $return = [];
        foreach ($entities as $entity) {
            if ($entity['type'] == Geography::TYPE_REGION) {
                $return[$entity['id']]['title'] = $entity['title'];
            } else if ($entity['type'] == Geography::TYPE_CITY) {
                if (!isset($return[$entity['parent_id']])) {
                    $return[$entity['parent_id']] = [];
                    $return[$entity['parent_id']]['items'] = [];
                }
                $return[$entity['parent_id']]['items'][$entity['id']] = $entity['title'];
            }
        }

        return $return;
    }
}