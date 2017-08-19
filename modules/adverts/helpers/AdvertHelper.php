<?php

namespace app\modules\adverts\helpers;

use app\modules\adverts\models\ar\Advert;

class AdvertHelper
{
    /**
     * @param Advert $model
     * @return string
     */
    public static function getPostContent($model)
    {
        $content = '';
        $content .= "Категория: {$model->category->name}\n";
        if ($model->geography) {
            $content .= "Место: {$model->geography->title}\n";
        }
        $content .= "Цена: " . self::stringifyPrice($model) . " \n\n";
        $content .= "{$model->content}\n\n";
        $content .= "Ссылка: {$model->fullUrl}";
        return $content;
    }

    /**
     * @param Advert $model
     * @return string
     */
    public static function stringifyPrice($model)
    {
        if ($model->min_price && $model->max_price) {
            $return = "{$model->min_price} - {$model->max_price}";
        } else if ($model->min_price && !$model->max_price) {
            $return = "от {$model->min_price}";
        } else if (!$model->min_price && $model->max_price) {
            $return = "до {$model->max_price}";
        }
        return isset($return) ? $return . ' ' . $model->currency->short_name : '';
    }
}