<?php

namespace app\modules\core\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Cookie;

class WidgetPageSize extends \yii\bootstrap\Widget
{
    /**
     * Cookie param name.
     *
     * @var string
     */
    const COOKIE_PARAM_NAME = '_widget_page_size';

    /**
     * Request param name to set page size.
     *
     * @var string
     */
    const PAGE_SIZE_PARAM_NAME = 'Widget-Page-Size';

    /**
     * Request param name to set page size.
     *
     * @var string
     */
    const WIDGET_ID_PARAM_NAME = 'Widget-Id';

    /**
     * @var integer default page size
     */
    public static $defaultValue = 20;

    /**
     * @var string event listeners will be delegated via 'body', so this plugin
     * will work even after grid separately loaded via AJAX (you can specify some
     * closer container to improve performance)
     */
    public $domContainer = 'body';

    /**
     * @var array
     */
    public $sizes = [
        5 => 5,
        10 => 10,
        20 => 20,
        50 => 50,
        100 => 100,
    ];

    /**
     * @var boolean whether current widget would work independently from other
     * widgets. That is changing of page size value for another widgets will
     * not affect current one
     */
    public $independentChanging = false;

    /**
     * @var string id of pjax
     */
    public $pjaxId;

    /**
     * @var string text "Records per page"
     */
    public $title = 'Кол-во записей: ';

    /**
     * @var string view file path
     */
    public $viewFile = '@app/modules/core/widgets/views/widget-page-size/index';

    /**
     * @var string url for changing page size
     * (default - Url::to(['widget-page-size']))
     */
    public $url;

    /**
     * @var string optional. Used only for "Clear filters" button. If not set,
     * then it will be guessed via $pjaxId
     */
    public $widgetId;


    /**
     * @var array
     */
    protected static $values;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!$this->url) {
            $this->url = Url::to(['/site/widget-page-size']);
        }

        if (!$this->widgetId) {
            // Remove "-pjax" from the end
            $this->widgetId = substr($this->pjaxId, 0, -5);
        }
    }

    /**
     * @throws \yii\base\InvalidConfigException
     * @return string
     */
    public function run()
    {
        if (!$this->pjaxId) {
            throw new InvalidConfigException('Missing pjaxId param');
        }

        $this->view->registerJs($this->js());

        return $this->renderContent();
    }

    /**
     * Renders widget content.
     * @return string content
     */
    public function renderContent()
    {
        $content = Html::tag('span', $this->title);
        $content .= implode(', ', $this->getLinks());

        return Html::tag('div', $content, [
            'class' => 'widget-page-size'
        ]);
    }

    /**
     * Renders page size links.
     * @return array
     */
    protected function getLinks()
    {
        $links = [];
        foreach ($this->sizes as $size) {
            $isActive = self::getPageSize($this->widgetId) == $size ? true : false;
            if ($isActive) {
                $links[] = Html::tag('span', $size, [
                    'class' => 'active',
                ]);
            } else {
                $links[] = Html::a($size, '', [
                    'data-widget-page-size' => $size,
                    'data-widget-id' => $this->widgetId,
                ]);
            }
        }
        return $links;
    }

    /**
     * @return string
     */
    protected function js()
    {
        $pageSizeHeaderName = self::PAGE_SIZE_PARAM_NAME;
        $widgetIdHeaderName = self::WIDGET_ID_PARAM_NAME;
        $js = <<<JS
jQuery(document).on('pjax:beforeSend', function(data, xhr, options) {
    var target = $(options.target ? options.target : null);
    if (target.attr('data-widget-page-size')) {
        xhr.setRequestHeader('{$pageSizeHeaderName}', target.attr('data-widget-page-size'));
        xhr.setRequestHeader('{$widgetIdHeaderName}', target.attr('data-widget-id'));
    }
});
JS;
    /* Для изменения через дополнительный запрос
    $('$this->domContainer').off('click', '.widget-page-size span.link').on('click', '.widget-page-size span.link', function (e) {
        var self = $(this);
        $.post(self.attr('data-url')).done(function() {
            $.pjax.reload({container: '#$this->pjaxId', timeout : 1000});
        });
    });
    */

        return $js;
    }

    /**
     * Set widget page size via cookie.
     * @param page $pageSize page size
     * @param type $widgetId
     * @return void
     */
    public static function setPageSize($pageSize, $widgetId = null)
    {
        $values = self::getCookieValues();

        $values = !is_array($values) ? [] : $values;
        $values[$widgetId] = $pageSize;

        self::setCookieValues($values);
    }

    /**
     * Returns widget page size from cookie if exists
     * @param string $widgetId widget id
     * @return integer page size
     */
    public static function getPageSize($widgetId = null)
    {
        $values = self::getCookieValues();

        if (!$value = ArrayHelper::getValue($values, $widgetId, null)) {
            $value = ArrayHelper::getValue($values, null, null);
        }

        return $value ? : self::$defaultValue;
    }

    /**
     * Returns list of values from cookies.
     * @return array values
     */
    public static function getCookieValues()
    {
        if (!self::$values) {
            self::$values = Yii::$app->request->cookies->getValue(self::COOKIE_PARAM_NAME, []);
        }

        return self::$values;
    }

    /**
     * Sets list of values to cookies.
     * @param array $values
     */
    public static function setCookieValues($values)
    {
        self::$values = $values;

        Yii::$app->response->cookies->add(new Cookie([
            'name' => self::COOKIE_PARAM_NAME,
            'value' => $values,
            'expire' => time() + 86400 * 365, // 1 year
        ]));
    }

    /**
     * Multilanguage translating.
     */
    public static function t($message, $params = [], $language = null)
    {
        if (!isset(Yii::$app->i18n->translations['roman444uk/files/*'])) {
            Yii::$app->i18n->translations['widgets/WidgetPageSize/*'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'ru-RU',
                'basePath' => 'roman444uk/yii/messages',
                'fileMap' => [
                    'widgets/WidgetPageSize/app' => 'WidgetPageSize.php',
                ],
            ];
        }

        return Yii::t('widgets/WidgetPageSize/app', $message, $params, $language);
    }
}