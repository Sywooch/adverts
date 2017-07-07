<?php

use roman444uk\yii\widgets\ActiveForm;
use yii\helpers\Html;
    
$searchModel = $this->params['searchModel'];

?>

<div id="search-container">
    <?php
        $form = ActiveForm::begin([
            'id' => 'search-form',
            'method' => 'get',
            'action' => '/'
        ]);

            echo $form->field($this->params['searchModel'], 'content', [
                'template' => '{input}',
                'options' => [
                    'tag' => false
                ]
            ])->textInput([
                'name' => (!$this->params['directPopulating']) ? 'content' : null,
            ]);

            echo Html::submitInput('', [
                'class' => 'icon sm zoom'
            ]);

        ActiveForm::end();
    ?>
</div>

<?php

$js = <<<JS
jQuery('#search-form').on('submit.yiiActiveForm', function(event) {
    $.pjax.submit(event, '#advert-list-pjax');
});
JS;

$this->registerJs($js);