<?php

namespace app\modules\core\db;

class ActiveRecord extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return '';
    }

    /**
     * @param string $attribute
     * @param string|null $value
     * @return mixed
     */
    public static function getAttributeLabels($attribute, $value = null)
    {
        $config = static::attributeLabelsConfig();
        if (isset($config[$attribute])) {
            if ($value === null) {
                return $config[$attribute];
            } else if (isset($config[$attribute][$value])) {
                return $config[$attribute][$value];
            }
        }
        return $value;
    }

    /**
     * @return array
     */
    public static function attributeLabelsConfig()
    {
        return [];
    }
}