<?php

/**
 * @var \app\modules\adverts\models\ar\Advert $model
 * @var \app\modules\adverts\models\ar\AdvertTemplet $templet
 * @var \yii\web\View $this
 */

use app\modules\adverts\AdvertsModule;

?>

<?php if (Yii::$app->session->getFlash('success')): ?>
    <div class="alert alert-success text-center">
        <?= AdvertsModule::t('Ваше объявление сохранено и будет опубликовано после одобрения администрацией в кратчайшие сроки.'); ?>
    </div>
<?php else: ?>
    <?= $this->render('_form', compact('model', 'templet')) ?>
<?php endif ?>