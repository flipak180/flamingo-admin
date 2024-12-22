<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Achievements\AchievementCategory $model */

$this->title = 'Update Achievement Category: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Achievement Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="achievement-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
