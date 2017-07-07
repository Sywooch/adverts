<?php

use yii\helpers\Html;

use roman444uk\likes\components\LikeGhostHtml;
use roman444uk\bookmarks\components\BookmarkGhostHtml;

?>

<h2><?= $this->title ?></h2>

<div id="advert-view">
    
    <div class="user-block">
        <img  class="avatar" src="<?= $model->owner ? $model->owner->profile->avatarUrl : '' ?>">
        <span class="name">
            <?= $model->owner ? $model->owner->username : '' ?>
        </span>
    </div>
    
    <div class="content">
        <?= $model->content ?>
    </div>
    
    <div class="clear"></div>
    
    <ul class="advert-files-list">
        <?php foreach ($model->files as $file): ?>
            <?= $this->render('/file/view', ['model' => $file]) ?>
        <?php endforeach ?>
    </ul>

    <div class="advert-info-block">
        <span class="date">
            <?= Yii::$app->formatter->asDatetime(date('Y-m-d', $model->createdAt)) ?>
        </span>
    </div>

    <?= BookmarkGhostHtml::toggleButton('advert/bookmark', $model->id, $model->bookmarked, [
        'data-pjax' => 0,
        'class' => $model->bookmarked ? 'bookmarked' : '',
    ]) ?>
</div>