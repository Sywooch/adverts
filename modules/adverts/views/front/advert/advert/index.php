<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\adverts\models\ar\Advert
 * @var $owner \app\modules\users\models\ar\User
 * @var $profile \app\modules\users\models\ar\Profile
 * @var $renderPartial bool
 */

use yii\helpers\Html;
use app\modules\adverts\AdvertsModule;
use app\modules\adverts\helpers\AdvertHelper;
use app\modules\adverts\widgets\MultiGallery;
use app\modules\core\widgets\ActiveForm;
use app\modules\core\widgets\BookmarkButtonWidget;
use app\modules\core\widgets\CommentButtonWidget;
use app\modules\core\models\ar\Comment;
use app\modules\core\widgets\LikeButtonWidget;
use app\modules\core\widgets\LookButtonWidget;

?>

<?= $this->render('header', [
    'model' => $model
]); ?>

<?= Html::tag('p', $model->content, [
    'class' => 'advert-content mt-5 mb-5',
    'target' => '_blank',
    'data-pjax' => 0,
]); ?>


<?php
if ($model->files) {
    $files = [];
    foreach ($model->files as $file) {
        $files[] = [
            'src' => $file->url,
            'imageOptions' => [
                'class' => 'img-rounded',
            ]
        ];
    }
    echo MultiGallery::widget([
        'options' => [
            'class' => 'files-list',
            'data-action' => 'files-list'
        ],
        'items' => $files
    ]);
}
?>

    <div class="row">
        <div class="info col-xs-12 col-sm-12 col-md-7 col-lg-7 text-right-xs text-right-sm">
            <?php if ($model->category): ?>
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

            <?php if ($priceString = AdvertHelper::stringifyPrice($model)): ?>
                <span>|</span>
                <span class="price" title="<?= Yii::t('app', 'Цена'); ?>">
                <?= $priceString; ?>
            </span>
            <?php endif; ?>
        </div>

        <div class="actions actions-bottom col-xs-12 col-sm-12 col-md-5 col-lg-5 text-right info" data-action="actions">
            <?= LookButtonWidget::widget([
                'model' => $model
            ]); ?>

            <?= CommentButtonWidget::widget([
                'model' => $model
            ]); ?>

            <?= LikeButtonWidget::widget([
                'model' => $model,
                'action' => LikeButtonWidget::ACTION_LIKE,
                'primaryContainerSelector' => $renderPartial ? '.adverts-list' : '.advert-view',
            ]); ?>

            <?= LikeButtonWidget::widget([
                'action' => LikeButtonWidget::ACTION_DISLIKE,
                'model' => $model,
                'primaryContainerSelector' => $renderPartial ? '.adverts-list' : '.advert-view',
            ]); ?>

            <?= BookmarkButtonWidget::widget([
                'model' => $model,
                'primaryContainerSelector' => $renderPartial ? '.adverts-list' : '.advert-view',
            ]); ?>
        </div>

        <?php if (false): ?>
            <div class="actions actions-top">
                <?php if ($model->user_id == Yii::$app->user->id) {
                    echo Html::a(
                        '<i class="glyphicon glyphicon-edit"></i>&nbsp;',
                        ['/adverts/advert/update', 'id' => $model->id],
                        [
                            'title' => Yii::t('app', 'Редактировать'),
                            'target' => '_blank',
                            'data-pjax' => 0
                        ]
                    );
                } ?>
            </div>
        <?php endif; ?>
    </div>

<?php if (false): ?>
    <div class="row mt-20">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
            <?php $form = ActiveForm::begin([
                'id' => 'advert-add-comment-form',
                'action' => [
                    '/adverts/advert/comment-add',
                    'modelId' => $model->id,
                    'modelName' => $model::shortClassName(),
                ],
                'enableClientValidation' => true,
                'enableAjaxValidation' => true,
                'validateOnBlur' => false,
                'validateOnChange' => false,
                'options' => [
                    'class' => 'advert-form'
                ],
                'fieldConfig' => [
                    'template' => "{input}",
                    'inputOptions' => [
                        'class' => 'advert-add-comment-form input-sm'
                    ]
                ]
            ]); ?>

            <div class="row">
                <div class="col-lg-12">
                    <?= $form->field(new Comment(),'text')->textarea([
                        'style' => 'width: 100%',
                        'rows' => 1
                    ]); ?>
                </div>
            </div>

            <div class="btn-group">
                <?= Html::submitButton(AdvertsModule::t('Отправить комментарий'), [
                    'class' => 'btn btn-success btn-sm'
                ]); ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php if (count($model->comments)): ?>
                <div class="row comments-list">
                    <?php foreach ($model->comments as $comment): ?>
                        <div class="comment-container in-row mt-30">
                            <?= $this->render('comment-header', [
                                'model' => $comment
                            ]); ?>
                            <?= Html::tag('p', $comment->text, [
                                'class' => 'mt-5 mb-5'
                            ]); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>