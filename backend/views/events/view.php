<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Event $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'События', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="event-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'event_id' => $model->event_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'event_id' => $model->event_id], [
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
            'event_id',
            'title',
            'subtitle',
            'place_id',
            'description:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>
</div>
