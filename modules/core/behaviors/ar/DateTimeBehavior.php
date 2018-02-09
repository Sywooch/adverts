<?php

namespace app\modules\core\behaviors\ar;

use app\modules\core\helpers\DateTimeHelper;
use Yii;
use yii\base\Behavior;
use yii\base\ModelEvent;
use yii\validators\Validator;
use app\modules\core\db\ActiveRecord;

class DateTimeBehavior extends Behavior
{
    /**
     * @var array
     * Массив атрибутов типа timestamp
     */
    public $datetimeAttributes = [];

    /**
     * @var array
     * Массив атрибутов типа date
     */
    public $dateAttributes = [];

    /**
     * @var array
     * Массив атрибутов типа timestamp с локальной временной зоной/
     */
    public $localDatetimeAttributes = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'formatAttributes',
            ActiveRecord::EVENT_BEFORE_INSERT   => 'formatAttributes',
            ActiveRecord::EVENT_BEFORE_UPDATE   => 'formatAttributes',
        ];
    }

    /**
     * @param ModelEvent $event
     * Добавляем date валидатор для дат
     */
    public function formatAttributes($event)
    {
        /* @var $owner \yii\db\ActiveRecord */
        $owner = $event->sender;

        $this->formatToDb($owner, $this->datetimeAttributes, 'Y-m-d H:i:s'); // P - метка временной зоны
        $this->formatToDb($owner, $this->dateAttributes, 'Y-m-d');
    }

    /**
     * @param $owner \yii\db\ActiveRecord
     * @param $attributes []
     * @param $format string
     */
    protected function formatToDb($owner, $attributes, $format)
    {
        foreach ($attributes as $attribute) {
            $value = DateTimeHelper::convertNamesToSystem($owner->{$attribute});
            if (is_string($value)) {
                if (!empty($value)) {
                    $value = date($format, strtotime($value));
                } else {
                    $value = null;
                }
                $owner->{$attribute} = $value;
            }
        }
    }

    /**
     * @param string $attribute
     * @return string
     */
    public function getFormattedDatetime($attribute)
    {
        if (in_array($attribute, $this->datetimeAttributes)) {
            return Yii::$app->formatter->asDatetime($this->owner->{$attribute});
        }
        if (in_array($attribute, $this->dateAttributes)) {
            return Yii::$app->formatter->asDate($this->owner->{$attribute});
        }
        if (in_array($attribute, $this->localDatetimeAttributes)) {
            return DateHelper::toTimezone($this->owner->{$attribute}, $this->owner->timezone->key);
        }
        return '';
    }
}
