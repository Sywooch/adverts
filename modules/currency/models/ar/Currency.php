<?php

namespace app\modules\currency\models\ar;

/**
 * This is the model class for table "currency".
 *
 * @property integer $id
 * @property string $code
 * @property string $short_name
 * @property string $sign
 */
class Currency extends \app\modules\core\db\ActiveRecord
{
    const EUR = 'EUR';
    const RUB = 'RUB';
    const UAH = 'UAH';
    const USD = 'USD';

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
            [['code', 'short_name'], 'required'],
            [['code'], 'string', 'max' => 3],
            [['sign'], 'string', 'max' => 12],
            [['short_name'], 'string', 'max' => 8],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Код',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRates()
    {
        return $this->hasMany(CurrencyRate::className(), ['src_id' => 'id']);
    }

    /**
     * Returns currency symbol by its code.
     * @param string $code
     * @return string mixed
     */
    public static function getSignByCode($code)
    {
        $signs = [self::UAH => '₴', self::USD => '$', self::EUR => '€', self::RUB => '₽'];
        return $signs[$code];
    }


    /**
     * Returns full currency name in prepositional case.
     * @param string $code
     * @return string
     */
    public static function getFullPrepositionalName($code)
    {
        $signs = [self::UAH => 'гривнах', self::USD => 'долларах', self::EUR => 'евро', self::RUB => 'рублях'];
        return $signs[$code];
    }
}