<?php

use common\models\Place;
use common\models\Tag;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\PlacesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Места';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="place-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить место', ['create'], ['class' => 'btn btn-success']) ?>
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
            'title',
            //'description:ntext',
            [
                'attribute' => 'tags_field',
                'format' => 'raw',
                'value' => function(Place $model) {
                    return $model->getTagsLabels();
                },
                'filter' => Html::activeDropDownList($searchModel, 'tags_field', ArrayHelper::map(Tag::find()->orderBy('title ASC')->all(), 'id', 'title'), ['class' => 'form-control', 'prompt' => '']),
            ],
            'latitude',
            'longitude',
            'radius',
            //'in_trash',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
                'headerOptions' => ['style' => 'width: 200px;'],
            ],
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Place $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
</div>
