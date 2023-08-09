<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Compilation $model */

$this->title = 'Добавление подборки';
$this->params['breadcrumbs'][] = ['label' => 'Подборки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="compilation-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
