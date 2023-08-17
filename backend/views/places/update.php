<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Place $model */

$this->title = 'Редактирование места: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Места', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>

<div class="place-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
