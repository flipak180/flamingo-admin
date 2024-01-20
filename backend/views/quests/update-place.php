<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\QuestPlace $model */

$this->title = 'Редактирование места квеста: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Квесты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->quest->title, 'url' => ['update', 'id' => $model->quest_id]];
$this->params['breadcrumbs'][] = $model->title;
?>

<div class="quest-place-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form-place', [
        'model' => $model,
    ]) ?>
</div>
