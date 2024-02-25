<?php

use common\models\Quest;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\QuestsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Квесты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="quest-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить квест', ['create'], ['class' => 'btn btn-success']) ?>
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
                'value' => function(Quest $model) {
                    return Html::a($model->title, ['quests/update', 'id' => $model->id]);
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
                'urlCreator' => function ($action, Quest $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
                'template' => '{delete}'
            ],
        ],
    ]); ?>
</div>
