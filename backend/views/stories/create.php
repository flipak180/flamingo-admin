<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Stories\Story $model */

$this->title = 'Добавление истории';
$this->params['breadcrumbs'][] = ['label' => 'Истории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="story-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
