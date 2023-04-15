<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Category $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="category-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->category_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->category_id], [
            'class' => 'btn btn-danger',
            'data' => ['confirm' => 'Вы уверены?'],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'category_id',
            'title',
            'image',
            'type',
            [
                'attribute' => 'parent_id',
                'format' => 'raw',
                'value' => $model->parent ? Html::a($model->parent->title, ['users/view', 'id' => $model->parent_id]) : '-',
            ],
            // 'in_trash',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
</div>
