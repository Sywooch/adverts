<?php

use yii\helpers\Html;
use yii\helpers\Url;

use roman444uk\files\models\File;
use roman444uk\yii\widgets\ActiveForm;

?>

<div class="dz-preview dz-file-preview">
    <?php
        switch ($model->type) {
            case File::TYPE_IMAGE:
                echo Html::img($model->absoluteUrl, [
                    'data-dz-thumbnail' => $model->id
                ]);
                break;
            case File::TYPE_VIDEO:
                echo Html::tag('video', '<source src="' . $model->getAbsoluteThumbUrl() . '">');
                break;
        }
    ?>
    
    <?= Html::a('', '', [
        'id' => "advert-file-{$model->id}",
        'class' => 'anchor'
    ]) ?>
    
    <?php $form = ActiveForm::begin([
        'id' => 'advert-file-form',
        'action' => Url::to(['/file/update', 'id' => $model->id]),
        'enableClientValidation' => false,
        'fieldConfig' => [
            'template' => "{input}"
        ]
    ]) ?>
    
        <div class="panel">
            <?= Html::submitButton(Yii::t('app', 'Save')) ?>
            
            <?= Html::a(Yii::t('app', 'Delete'), Url::to([
                    '/file/delete', 'id' => $model->id
                ]), [
                    'class' => 'button',
                    'data-delete-file' => $model->id
            ]) ?>
        </div>
    
        <?php echo $form->field($model, 'description', [
            'options' => ['tag' => false]
        ])->textarea() ?>
    
    <?php ActiveForm::end() ?>
    
    <div class="dz-progress">
        <span class="dz-upload" data-dz-uploadprogress></span>
    </div>
    <div class="dz-error-message">
        <span data-dz-errormessage></span>
    </div>
</div>