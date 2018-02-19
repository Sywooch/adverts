<?php

use app\modules\adverts\models\search\AdvertSearch;
use app\modules\core\web\View;

use yii\helpers\Html;

/**
 * @var AdvertSearch $model
 * @var View $this
 * @var array $options
 * @var string $expandIcons
 */

?>

<?= Html::tag('label', $model->getAttributeLabel('geography_id') . $expandIcons, $options); ?>