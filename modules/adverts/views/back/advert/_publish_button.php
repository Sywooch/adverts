<?php

/**
 * @var $this yii\web\View
 * @var $model \app\modules\adverts\models\ar\Advert
 */

use yii\helpers\Html;
use app\modules\adverts\AdvertsModule;
use app\modules\adverts\models\ar\Advert;
use yii\helpers\Url;

?>

<div class="row">
    <div class="col-lg-12 text-right">
        <?= Html::button(AdvertsModule::t('Опубликовать'), [
            'class' => 'btn btn-success btn-sm',
            'data-action' => 'advert-publish',
            'data-url' => Url::to(['/adverts/advert/update', 'id' => $model->id]),
            'data-pjax' => 0,
        ]); ?>
    </div>
</div>

<?php
    /*$status = Advert::STATUS_ACTIVE;
    $js = <<<JS
jQuery('[data-action=advert-publish]').on('click', function(e) {
    var self = $(this);
    var data = {
        'status': '{$status}'
    };
    $.ajax({
        url: self.attr('data-url'), 
        method: 'POST',
        dataType: 'json',
        data: data,
        success: function(data, textStatus, jqXHR) {
            if (data.success) {
                $.pjax.reload({container: '#advert-grid-pjax'});
                $('#advert-grid-modal').modal('hide').find('.modal-body').html('');
            }            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            alert('Error! See firebug..');          
        }
    });
    e.preventDefault();
});
JS;
    $this->registerJs($js);*/
?>