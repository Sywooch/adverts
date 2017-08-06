<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\adverts\models\ar\Advert
 * @var $owner \app\modules\users\models\ar\User
 * @var $profile \app\modules\users\models\ar\Profile
 * @var $renderPartial bool
 */

use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\adverts\AdvertsModule;
use app\modules\core\widgets\BookmarkButtonWidget;
use app\modules\core\widgets\CommentButtonWidget;
use app\modules\core\widgets\LikeButtonWidget;
use app\modules\core\widgets\LookButtonWidget;

$owner = $model->owner;
$profile = $owner->profile;
$userId = Yii::$app->user->id;

?>

<img class="user-avatar img-circle" src="<?= $profile->avatarUrl; ?>">

<?= $profile->fullName; ?>
<?php /*Html::a($profile->fullName, $profile->url, [
    'class' => 'user-fullname'
]);*/ ?>


<?= Html::a($model->content, Url::to(['/adverts/advert/view', 'id' => $model->id]), [
    'class' => 'content',
    'target' => '_blank',
    'data-pjax' => 0,
]); ?>

<div class="clear"></div>

<ul class="files-list">
    <?php if ($model->files): ?>
        <?php foreach ($model->files as $file): ?>
            <?= $this->render('@frontend/views/file/view', ['model' => $file]) ?>
        <?php endforeach ?>
    <?php endif; ?>
</ul>

<!--<div class="add-comment">
    <form>
        <textarea type="text" name="comment" value=""></textarea>
    </form>
</div>-->

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 text-right-xs text-right-sm info">
        <span class="date" title="<?= Yii::t('app', 'Опубликовано') ?>">
            <?= Yii::$app->formatter->asDatetime($model->created_at); ?>
        </span>

        <?php if ($model->category): ?>
            <span>|</span>
            <span class="city" title="<?= Yii::t('app', 'Категория'); ?>">
                <?= $model->category->name; ?>
            </span>
        <?php endif; ?>

        <?php if ($model->cityName): ?>
            <span>|</span>
            <span class="city" title="<?= Yii::t('app', 'Город'); ?>">
                <?= $model->cityName; ?>
            </span>
        <?php endif; ?>

        <?php
            $priceString = null;
            if ($model->min_price && $model->max_price) {
                $priceString = "{$model->min_price} - {$model->max_price}";
            } else if ($model->min_price && !$model->max_price) {
                $priceString = "от {$model->min_price}";
            } else if (!$model->min_price && $model->max_price) {
                $priceString = "до {$model->max_price}";
            }
        ?>

        <?php if ($priceString): ?>
            <span>|</span>
            <span class="price" title="<?= Yii::t('app', 'Цена'); ?>">
                <?= $priceString; ?> <?= $model->currency->sign; ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="actions col-xs-12 col-sm-12 col-md-5 col-lg-5 text-right info" data-action="actions">
        <?= LookButtonWidget::widget([
            'model' => $model
        ]); ?>

        <?= CommentButtonWidget::widget([
            'model' => $model
        ]); ?>

        <?= LikeButtonWidget::widget([
            'model' => $model,
            'action' => LikeButtonWidget::ACTION_LIKE,
            'primaryContainerSelector' => '.adverts-list',
        ]); ?>

        <?= LikeButtonWidget::widget([
            'action' => LikeButtonWidget::ACTION_DISLIKE,
            'model' => $model,
            'primaryContainerSelector' => '.adverts-list',
        ]); ?>
        &nbsp;
        <?= BookmarkButtonWidget::widget([
            'model' => $model,
            'primaryContainerSelector' => '.adverts-list',
        ]); ?>
        &nbsp;
    </div>
</div>