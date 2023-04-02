<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Place $model */

$this->title = 'Добавление места';
$this->params['breadcrumbs'][] = ['label' => 'Места', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="place-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
