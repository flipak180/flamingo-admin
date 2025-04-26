<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\PetersEyes\PetersEye $model */

$this->title = 'Редактирование игры №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Глазами Петра', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Игра ' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="peters-eye-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
