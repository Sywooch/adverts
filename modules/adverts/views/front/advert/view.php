<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\adverts\models\ar\Advert
 */

?>

<h2><?= $this->title ?></h2>

<div class="adverts-list">
    <div class="advert-container">
        <?= $this->render('_advert', [
            'model' => $model
        ]); ?>
    </div>
</div>