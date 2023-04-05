<?php

use backend\widgets\YMaps\CoordsInput;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $widget CoordsInput */
/* @var $hasModel boolean */

?>

<?php if ($hasModel) : ?>
    <?= Html::activeTextInput($widget->model, $widget->attribute, $widget->options) ?>
<?php else : ?>
    <?= Html::textInput($widget->name, $widget->value, $widget->options) ?>
<?php endif; ?>

<div class="ymaps-map" id="<?= $widget->options['id'] ?>-map"></div>
