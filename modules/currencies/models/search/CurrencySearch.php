<?php

namespace app\modules\currencies\models\search;

use app\modules\currencies\models\ar\Currency;
use app\modules\currencies\CurrenciesModule;
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
            'name' => CurrenciesModule::t('Название'),
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
}