<?php

use app\modules\core\widgets\WidgetPageSize;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

$containerTag = ArrayHelper::remove($this->context->containerOptions, 'tag', 'div');
$clearButtonTag = ArrayHelper::remove($this->context->clearFiltersButtonOptions, 'tag', 'span');
?>

<?= Html::beginTag($containerTag, $this->context->containerOptions) ?>
<?php if ($this->context->enableClearFilters || true): ?>
    <?= Html::tag(
        $clearButtonTag,
        WidgetPageSize::t('Clear filters'),
        $this->context->clearFiltersButtonOptions
    ) ?>
<?php endif; ?>

<?= $this->context->text ?>

<?= Html::dropDownList(
    'widget-page-size',
    \Yii::$app->request->cookies->getValue('_widget_page_size', $this->context->defaultValue),
    ArrayHelper::remove($this->context->dropDownOptions, 'items', []),
    $this->context->dropDownOptions
) ?>
<?= Html::endTag($containerTag) ?>