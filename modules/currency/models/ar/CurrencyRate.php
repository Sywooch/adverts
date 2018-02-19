<?php

namespace app\modules\currency\models\ar;

use Yii;

/**
 * This is the model class for table "currency_rate".
 *
 * @property int $id
 * @property int $src_id
 * @property int $dst_id
 * @property string $value
 *
 * @property Currency $dstCurrency
 * @property Currency $srcCurrency
 */
class CurrencyRate extends \app\modules\core\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currency_rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['src_id', 'dst_id'], 'required'],
            [['src_id', 'dst_id'], 'integer'],
            [['value'], 'number'],
            [['dst_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['dst_id' => 'id']],
            [['src_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['src_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'src_id' => Yii::t('app', 'Src ID'),
            'dst_id' => Yii::t('app', 'Dst ID'),
            'value' => Yii::t('app', 'Value'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDstCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'dst_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSrcCurrency()
    {
        return $this->hasOne(Currency::className(), ['id' => 'src_id']);
    }
}
