<?php

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\adverts\models\search\AdvertSearch $searchModel
 * @var boolean $withFilter
 */

use app\modules\adverts\widgets\AdvertList;
use yii\widgets\Pjax;
use app\modules\adverts\widgets\AdvertListLinkSorter;

?>

<?= $this->render('_filter', [
    'model' => $searchModel,
]); ?>

<?php Pjax::begin(['id' => 'adverts-list-pjax']); ?>

    <?= AdvertList::widget([
        'id' => 'adverts-list',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showOnEmpty' => true,
        'options' => [
            'class' => 'adverts-list'
        ],
        'itemOptions' => [
            'class' => 'advert-container',
        ],
        'sorter' => [
            'class' => AdvertListLinkSorter::className(),
            'attributes' => [
                'created_at',
                'updated_at',
                'min_price',
            ],
        ]
    ]); ?>
<?php Pjax::end() ?>