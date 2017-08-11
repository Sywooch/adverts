<?php

namespace app\modules\adverts\widgets;

use dosamigos\gallery\GalleryAsset;
use yii\base\Exception;
use yii\helpers\Json;

/**
 * Class MultiGallery renders BueImp widget several time on a page.
 * @package app\modules\adverts\widgets
 */
class MultiGallery extends \dosamigos\gallery\Gallery
{
    /**
     * @var bool
     */
    protected static $_initialized = false;

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->renderItems();

        if (!self::$_initialized) {
            self::$_initialized = true;
            echo $this->renderTemplate();
            $this->registerClientScript();
        }
    }

    /**
     * @inheritdoc
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        GalleryAsset::register($view);

        $id = $this->options['id'];
        $options = Json::encode($this->clientOptions);

        if (empty($this->options['data-action'])){
            throw new Exception('Please specify data-action property in widget options');
        }
        $selector = '[data-action=' . $this->options['data-action'] . ']';

        $js = <<<JS
jQuery(document).on('click', '{$selector} a', function(event) {
    var options = {$options};
    options['index'] = this;
    options['event'] = event;
    blueimp.Gallery($(this).parent('$selector').find('a'), options);
    event.preventDefault();
});
JS;

        $view->registerJs($js);

        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('$id').on('$event', $handler);";
            }
            $view->registerJs(implode("\n", $js));
        }
    }
}