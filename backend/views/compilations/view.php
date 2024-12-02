<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var \common\models\Compilations\Compilation $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Подборки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="compilation-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'compilation_id' => $model->compilation_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'compilation_id' => $model->compilation_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'compilation_id',
            'title',
            'image',
            'description:ntext',
            'in_trash:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>
</div>
