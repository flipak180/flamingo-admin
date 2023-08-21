<?php

use common\models\Event;
use common\models\Place;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\EventsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'События';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="event-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Новое событие', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'event_id',
                'headerOptions' => ['style' => 'width: 75px;'],
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function(Event $model) {
                    return Html::a($model->title, ['events/update', 'event_id' => $model->event_id]);
                },
            ],
            'subtitle',
            [
                'attribute' => 'place_id',
                'format' => 'raw',
                'value' => function(Event $model) {
                    return $model->place ? Html::a($model->place->title, ['places/view', 'id' => $model->place_id]) : '-';
                },
                'filter' => Html::activeDropDownList($searchModel, 'place_id', ArrayHelper::map(Place::find()->orderBy('title ASC')->all(), 'category_id', 'title'), ['class' => 'form-control', 'prompt' => '']),
            ],
            //'description:ntext',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
                'headerOptions' => ['style' => 'width: 200px;'],
            ],
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Event $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'event_id' => $model->event_id]);
                },
                'template' => '{delete}'
            ],
        ],
    ]); ?>
</div>
