<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PushNotification $model */

$this->title = 'Добавление пуша';
$this->params['breadcrumbs'][] = ['label' => 'Пуши', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="push-notification-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
