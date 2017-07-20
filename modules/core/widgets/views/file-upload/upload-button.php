<?php

/** @var \app\modules\core\widgets\FileUpload $this */
/** @var string $input the code for the input */

?>

<span class="btn btn-success fileinput-button">
   <i class="glyphicon glyphicon-plus"></i>
   <span><?= Yii::t('app','Добавить файл'); ?></span>
    <?= $input ?>
</span>
