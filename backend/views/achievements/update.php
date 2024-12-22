<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Achievements\Achievement $model */

$this->title = 'Редактирование достижения: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Достижения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="achievement-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
