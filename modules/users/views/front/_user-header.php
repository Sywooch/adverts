<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\core\db\ActiveRecord
 */

use yii\helpers\Html;

?>

<div class="row user-header">
    <div class="in-row">
        <?= Html::img($model->user->profile->avatarUrl, [
            'class' => 'avatar img-circle'
        ]); ?>
    </div>

    <?= Html::tag('div', $model->user->profile->fullName, [
        'class' => 'fullname'
    ]); ?>

    <div class="clear"></div>

    <?= Html::tag('div', Yii::$app->formatter->asDatetime($model->created_at), [
        'class' => 'time'
    ]); ?>
</div>