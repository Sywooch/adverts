<?php

namespace app\modules\currency\models\search;

use app\modules\currency\models\ar\Currency;
use Yii;
use yii\data\ActiveDataProvider;

class CurrencySearch extends Currency
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
        $query = Currency::find();

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
        return Currency::find()
            ->orderBy('short_name')
            ->asArray()
            ->all();
    }
}