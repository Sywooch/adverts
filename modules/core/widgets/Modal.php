<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\core\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Modal extends \yii\bootstrap\Modal
{
    /**
     * @var string
     */
    public $openButtonSelector;

    /**
     * @var bool
     */
    protected static $_initialized = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerClientScripts();
        $this->renderLoader();
    }

    /**
     * Renders modal loading html element.
     */
    public function renderLoader()
    {
        if (!self::$_initialized) {
            self::$_initialized = true;
            $loader = Html::tag('div', '<i class="fa fa-refresh fa-spin"></i>', [
                'class' => 'loader-image-container'
            ]);
            echo Html::tag('div', $loader, [
                'class' => 'modal-loader modal-backdrop fade in'
            ]);
        }
    }

    /**
     * Registers clients scripts.
     */
    protected function registerClientScripts()
    {
        if ($this->openButtonSelector) {
            $js = <<<JS
$(document).on('click', '$this->openButtonSelector', function(e) {
    var self = $(this);
    $('.modal-loader').appendTo($('body')).show();
    $('#{$this->id}').find('.modal-body').load(self.attr("href"), [], function(responseText, textStatus, jqXHR) {
        $('#{$this->id}').modal('show');
        $('.modal-loader').hide();
    });
    e.preventDefault();
});
$('#{$this->id}').on('hidden.bs.modal', function (e) {
    $('#{$this->id}').find('.modal-body').html('');
});
JS;
            $this->view->registerJs($js);
        }
    }
}
