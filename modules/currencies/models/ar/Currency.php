<?php

namespace app\modules\currencies\models\ar;

use app\modules\currencies\models\aq\CurrencyQuery;

/**
 * This is the model class for table "currency".
 *
 * @property integer $id
 * @property string $name
 * @property string $abbreviaton
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
            [['name', 'abbreviaton', 'sign'], 'required'],
            [['name'], 'string', 'max' => 32],
            [['abbreviaton'], 'string', 'length' => 3],
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
            'name' => 'Name',
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