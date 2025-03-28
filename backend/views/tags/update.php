<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Tags\Tag $model */

$this->title = 'Редактирование тега: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->tag_id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="tag-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
