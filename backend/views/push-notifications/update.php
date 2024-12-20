<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PushNotification $model */

$this->title = 'Редактирование пуша: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Пуши', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="push-notification-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
