<?php

use yii\helpers\Html;

use common\models\Advert;
use common\models\AdvertFile;
use common\models\City;

?>

<h3>Редактирование объявления № <?= $model->id ?>:</h3>

<?php echo $this->render('_form', compact('model', 'directPopulating')) ?>