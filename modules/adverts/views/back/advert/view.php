<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\adverts\models\ar\Advert
 */

$this->title = \app\modules\adverts\AdvertsModule::t('Просмотр объявления');

use app\modules\adverts\models\ar\Advert;

?>

<div class="advert-view">
    <div class="advert-container">
        <?php if ($model->status !== Advert::STATUS_ACTIVE && Yii::$app->user->isSuperadmin && Yii::$app->getIsEndSideBack()): ?>
            <?= $this->render('_publish_button', [
                'model' => $model
            ]); ?>
        <?php endif; ?>

        <?= $this->render('@app/modules/adverts/views/front/advert/_advert', [
            'model' => $model,
            'renderPartial' => false,
        ]); ?>
    </div>
</div>