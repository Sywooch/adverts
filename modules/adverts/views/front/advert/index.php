<?php

use app\modules\adverts\widgets\AdvertList;
use yii\widgets\Pjax;
use app\modules\adverts\widgets\AdvertListLinkSorter;

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \app\modules\adverts\models\search\AdvertSearch $searchModel
 * @var bool $renderFilter
 */

?>

<?php if ($renderFilter): ?>
    <?= $this->render('filter/index', [
        'model' => $searchModel,
    ]); ?>
<?php endif; ?>

<?php Pjax::begin(['id' => 'adverts-list-pjax']); ?>

    <?= AdvertList::widget([
        'id' => 'adverts-list',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
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