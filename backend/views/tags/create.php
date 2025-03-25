<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Tags\Tag $model */

$this->title = 'Добавление тега';
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tag-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
