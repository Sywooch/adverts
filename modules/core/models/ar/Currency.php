<?php

namespace app\modules\core\models\ar;

use app\modules\core\models\aq\CurrencyQuery;

/**
 * This is the model class for table "currency".
 *
 * @property integer $id
 * @property string $name
 * @property string $abbreviation
 * @property string $sign
 */
class Currency extends \app\modules\core\db\ActiveRecord
{
    const DOL = 'dol';
    const EUR = 'eur';
    const GRN = 'grn';
    const RUB = 'rub';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'short_name', 'abbreviation', 'sign'], 'required'],
            [['name'], 'string', 'max' => 32],
            [['short_name'], 'string', 'min' => 4, 'max' => 8],
            [['abbreviation'], 'string', 'length' => 3],
            [['sign'], 'string', 'max' => 12],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'short_name' => 'Сокращенное название',
        ];
    }

    /**
     * @inheritdoc
     * @return CurrencyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CurrencyQuery(get_called_class());
    }
}