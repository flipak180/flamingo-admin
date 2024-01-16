<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Quest $model */

$this->title = 'Добавление квеста';
$this->params['breadcrumbs'][] = ['label' => 'Квесты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="quest-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
