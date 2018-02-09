<?php

namespace app\modules\adverts\data;

use Yii;
use yii\base\BaseObject;
use yii\base\InvalidConfigException;
use yii\data\Sort;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\web\Request;

class AdvertSort extends Sort
{
    /**
     * @inheritdoc
     */
    public $enableMultiSort = true;

    /**
     * @inheritdoc
     */
    public $attributes = [
        'created_at' => [
            'asc' => ['created_at' => SORT_ASC],
            'desc' => ['created_at' => SORT_DESC],
            'label' => 'Дата создания',
            'default' => SORT_DESC,
        ],
        'updated_at' => [
            'asc' => ['updated_at' => SORT_ASC],
            'desc' => ['updated_at' => SORT_DESC],
            'label' => 'Дата последнего обновления',
            'default' => SORT_ASC,
        ],
        'min_price' => [
            'asc' => ['min_price' => SORT_ASC],
            'desc' => ['min_price' => SORT_DESC],
            'label' => 'Цена',
            'default' => SORT_ASC,
        ],
    ];

    /**
     * @inheritdoc
     */
    public $defaultOrder = [
        'created_at' => SORT_DESC,
        'updated_at' => SORT_DESC,
        'min_price' => SORT_ASC,
    ];
}
