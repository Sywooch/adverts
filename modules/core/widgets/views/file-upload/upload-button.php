<?php

/** @var \app\modules\core\widgets\FileUpload $this */
/** @var string $input the code for the input */

?>

<span class="btn btn-primary fileinput-button btn-sm">
   <i class="glyphicon glyphicon-plus"></i>
   <span><?= Yii::t('app','Добавить файл'); ?></span>
    <?= $input ?>
</span>
