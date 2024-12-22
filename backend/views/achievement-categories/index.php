<?php

use common\models\Achievements\AchievementCategory;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\AchievementCategoriesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Категории достижений';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="achievement-category-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить категорию достижений', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width: 75px;'],
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function(AchievementCategory $model) {
                    return Html::a($model->title, ['achievement-categories/update', 'id' => $model->id]);
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
                'headerOptions' => ['style' => 'width: 220px;'],
            ],
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, AchievementCategory $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
</div>
