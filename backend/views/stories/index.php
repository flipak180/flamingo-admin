<?php

use common\models\Stories\Story;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\StoriesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Истории';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="story-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить историю', ['create'], ['class' => 'btn btn-success']) ?>
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
                'value' => function(Story $model) {
                    return Html::a($model->title, ['stories/update', 'id' => $model->id]);
                },
            ],
            'link',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function(Story $model) {
                    return $model->getStatusName();
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', Story::getStatusesList(), ['class' => 'form-control', 'prompt' => '']),
            ],
            'created_at:datetime',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Story $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
</div>
