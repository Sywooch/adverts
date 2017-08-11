<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var \app\modules\core\models\ar\File $model
 * @var \yii\web\View $this
 * @var string $urlParam
 * @var string $deleteUrlParam
 */

?>

<div class="file-container" data-action="file-container">
    <?= Html::img($urlParam ? : "/uploaded/{$model->file_name}", [
        'class' => 'img-thumbnail'
    ]); ?>
    <div class="file-delete visible" data-action="file-delete" data-url="<?= Url::to([
        'file-delete', 'id' => $deleteUrlParam ? : $model->id
    ]); ?>">
        <i class="glyphicon glyphicon-remove"></i>
    </div>
    <div class="file-deleting" data-action="file-deleting">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>