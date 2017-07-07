<?php

use yii\helpers\Html;
use yii\helpers\Url;

$owner = $model->owner;
$userId = Yii::$app->user->id;

?>

<img  class="user-avatar" src="<?= $model->owner->profile->avatarUrl ?>">

<span class="user-name">
    <a href="<?= $owner->profileUrl ?>"><?= $owner->profile->name ?></a>
</span>

<a class="content" href="<?= Url::to(['/advert/view', 'id' => $model->id]) ?>">
    <?= $model->content ?>
</a>


<div class="clear"></div>

<ul class="advert-files-list">
    <?php if ($model->files): ?>
        <?php foreach ($model->files as $file): ?>
            <?= $this->render('@frontend/views/file/view', ['model' => $file]) ?>
        <?php endforeach ?>
    <?php endif; ?>
</ul>

<div class="add-comment">
    <form>
        <textarea type="text" name="comment" value=""></textarea>
    </form>
</div>

<div class="info">
    <span class="date" title="<?= Yii::t('app', 'Опубликовано') ?>">
        <?= Yii::$app->formatter->asDatetime($model->created_at) ?>
    </span>
    <span>|</span>
    <span class="city" title="<?= Yii::t('app', 'Город') ?>">
        <?= $model->cityName ?>
    </span>
    <div class="actions">
        <?php /*BookmarkGhostHtml::toggleButton('advert/toggle-bookmark', $model->id, $model->bookmarked, [
            'data-pjax' => 0,
            'class' => $model->isBookmarked ? 'bookmarked' : '',
        ])*/ ?>

        <?= Html::a('<i class="glyphicons glyphicons-heart"></i><span>' . $model->viewsCount . '</span>', [
            '/advert/comments',
        ], [
            'title' => Yii::t('app', 'Просмотров'), 'data-pjax' => 0
        ]) ?>

        <?= Html::a('<span class="glyphicons glyphicons-comments"></span><span>' . $model->commentsCount . '</span>', [
            '/advert-comment/index', 'id' => $model->id,
        ], [
            'title' => Yii::t('app', 'Комментарии'), 'data-pjax' => 0
        ]) ?>

        <i class="glyphicon glyphicon-heart"></i>
        <i class="glyphicon glyphicon-star"></i>
        <i class="glyphicon glyphicon-remove"></i>
        <i class="glyphicon glyphicon-eye-close"></i>
        <?= Html::a('<span>' . $model->likesCount . '</span>', [
            '/advert-comment/index', 'id' => $model->id,
        ], [
            'title' => Yii::t('app', 'Комментарии'), 'data-pjax' => 0
        ]) ?>
    </div>
</div>