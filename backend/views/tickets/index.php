<?php

use common\models\Ticket;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\TicketsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Тикеты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить тикет', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width: 75px;'],
                'format' => 'raw',
                'value' => function(Ticket $model) {
                    return Html::a($model->id, ['tickets/view', 'id' => $model->id]);
                },
            ],
            'user_id',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function(Ticket $model) {
                    return $model->getTypeTitle();
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', Ticket::getTypesList(), ['class' => 'form-control', 'prompt' => '']),
            ],
            'message:ntext',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
                'headerOptions' => ['style' => 'width: 220px;'],
            ],
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Ticket $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 },
                'template' => '{view}'
            ],
        ],
    ]); ?>


</div>
