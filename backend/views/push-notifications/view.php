<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\PushNotification $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Пуши', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="push-notification-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Отправить', ['send', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Тестировать', ['test', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
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
            'id',
            'title',
            'body',
            'link',
            'created_at',
            'updated_at',
        ],
    ]) ?>
</div>
