<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\adverts\models\ar\Advert
 */

?>

<h3>Редактирование объявления № <?= $model->id ?>:</h3>

<?php echo $this->render('_form', compact('model', 'directPopulating')) ?>