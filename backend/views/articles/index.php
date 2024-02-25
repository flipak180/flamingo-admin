<?php

use common\models\Article;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\ArticlesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
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
                'value' => function(Article $model) {
                    return Html::a($model->title, ['articles/update', 'id' => $model->id]);
                },
            ],
            'subtitle',
            //'description:ntext',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
                'headerOptions' => ['style' => 'width: 220px;'],
            ],
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Article $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{delete}'
            ],
        ],
    ]); ?>
</div>
