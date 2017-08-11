<?php

namespace app\modules\adverts\widgets;

use app\modules\core\db\ActiveRecord;

class AdvertListView extends \yii\widgets\ListView
{
    /**
     * @var ActiveRecord see GridView::$filterModel for more details
     */
    public $filterModel;
}