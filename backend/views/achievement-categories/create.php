<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Achievements\AchievementCategory $model */

$this->title = 'Create Achievement Category';
$this->params['breadcrumbs'][] = ['label' => 'Achievement Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="achievement-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
