<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\adverts\models\ar\Advert
 */

use app\modules\adverts\AdvertsModule;

?>

<?php if (Yii::$app->session->getFlash('success')): ?>
    <div class="alert alert-success text-center">
        <?= AdvertsModule::t('Объявление успешно изменено.'); ?>
    </div>
<?php endif ?>

<?= $this->render('_form', compact('model', 'templet')) ?>