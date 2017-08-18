<?php

namespace app\modules\core\widgets;

class Widget extends \yii\bootstrap\Widget
{
    /**
     * @var bool whether was initialized at least one instance of this widget
     */
    protected static $_initialized = [];

    /**
     * Enables initialize property.
     */
    protected static function initialize()
    {
        if (!self::isInitialized()) {
            self::$_initialized[] = get_called_class();
        }
    }

    /**
     * Checks whether was initialized at least one instance of this widget.
     * @return bool
     */
    protected static function isInitialized()
    {
        return in_array(get_called_class(), self::$_initialized);
    }
}