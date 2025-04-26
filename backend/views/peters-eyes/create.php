<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\PetersEyes\PetersEye $model */

$this->title = 'Добавление игры';
$this->params['breadcrumbs'][] = ['label' => 'Глазами Петра', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="peters-eye-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
