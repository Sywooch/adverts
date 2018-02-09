<?php

namespace app\modules\core\validators;

use app\modules\core\helpers\DateTimeHelper;

class DateValidator extends \yii\validators\DateValidator
{
    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = DateTimeHelper::convertNamesToSystem($model->$attribute);
        if ($this->isEmpty($value)) {
            if ($this->timestampAttribute !== null) {
                $model->{$this->timestampAttribute} = null;
            }
            return;
        }

        $timestamp = $this->parseDateValue($value);
        if ($timestamp === false) {
            if ($this->timestampAttribute === $attribute) {
                if ($this->timestampAttributeFormat === null) {
                    if (is_int($value)) {
                        return;
                    }
                } else {
                    if ($this->parseDateValueFormat($value, $this->timestampAttributeFormat) !== false) {
                        return;
                    }
                }
            }
            $this->addError($model, $attribute, $this->message, []);
        } elseif ($this->min !== null && $timestamp < $this->min) {
            $this->addError($model, $attribute, $this->tooSmall, ['min' => $this->minString]);
        } elseif ($this->max !== null && $timestamp > $this->max) {
            $this->addError($model, $attribute, $this->tooBig, ['max' => $this->maxString]);
        } elseif ($this->timestampAttribute !== null) {
            if ($this->timestampAttributeFormat === null) {
                $model->{$this->timestampAttribute} = $timestamp;
            } else {
                $model->{$this->timestampAttribute} = $this->formatTimestamp($timestamp, $this->timestampAttributeFormat);
            }
        }
    }
}
