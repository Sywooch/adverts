<?php

use yii\helpers\Html;

use common\models\Advert;
use common\models\AdvertFile;
use common\models\City;

?>

<?php echo $this->render('_form', compact('model', 'directPopulating')) ?>