<?php

use common\models\Compilation;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\CompilationsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Подборки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="compilation-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить подборку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'compilation_id',
                'headerOptions' => ['style' => 'width: 75px;'],
            ],
            'title',
            'image',
            //'description:ntext',
            //'in_trash:boolean',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
                'headerOptions' => ['style' => 'width: 200px;'],
            ],
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Compilation $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'compilation_id' => $model->compilation_id]);
                 }
            ],
        ],
    ]); ?>


</div>
