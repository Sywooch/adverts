<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\adverts\models\ar\Advert
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row user-header">
    <div class="in-row">
        <?= Html::img($model->user->profile->avatarUrl, [
            'class' => 'avatar img-circle'
        ]); ?>
    </div>

    <?= Html::a($model->user->profile->fullName, Url::to(['/users/user/view', 'id' => $model->user->id]), [
        'class' => 'fullname',
        'data-action' => 'user-view',
        'data-pjax' => 0,
        'target' => '_blank',
    ]); ?>

    <div class="clear"></div>

    <?= Html::tag('div', Yii::$app->formatter->asDatetime($model->created_at), [
        'class' => 'time'
    ]); ?>
</div>