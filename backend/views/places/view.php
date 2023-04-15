<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Place $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Места', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="place-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->place_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->place_id], [
            'class' => 'btn btn-danger',
            'data' => ['confirm' => 'Вы уверены?'],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'place_id',
            'title',
            'category_id',
            'description:ntext',
            // 'location',
            // 'in_trash',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
</div>
