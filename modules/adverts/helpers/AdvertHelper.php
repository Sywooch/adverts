<?php

namespace app\modules\adverts\helpers;

use app\modules\adverts\models\ar\Advert;
use Yii;

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
}