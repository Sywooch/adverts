

    <div id="file-upload-form">
        <?php roman444uk\jsAjaxFileUploader\JsAjaxFileUploader::widget([
            'id' => 'file-upload-form',
            'clientOptions' => [
                'uploadUrl' => Url::to('/file/upload'),
                'fileName' => Html::getInputName(new File, 'file'),
                'maxFileSize' => 512000,
                'allowExt' => 'gif|jpg|jpeg|png',
                'showProgress' => false,
                'inputText' => 'Добавить файл'
            ]
        ]) ?>
        
        <?php $form = ActiveForm::begin([
            'id' => 'advert-file-form',
            'enableClientValidation' => false,
            'fieldConfig' => [
                'template' => "{label}{input}"
            ]
        ]) ?>

            <?= $form->field(new File, 'file')->fileInput([
                'name' => ($directPopulating) ? 'file' : null
            ]) ?>
        
            <?= Html::submitButton(
                'Загрузить',
                ['class' => '']
            ) ?>

            <?php /* roman444uk\jqueryUpoadFilePlugin\JQueryUpoadFilePlugin::widget([
                'id' => 'file-upload-form',
                'renderContainer' => false,
                'clientOptions' => [
                    'url' => Url::to('/file/upload'),
                    'fileName' => Html::getInputName(new File, 'file'),
                ]
            ]); */ ?>


        <?php ActiveForm::end() ?>
    </div>