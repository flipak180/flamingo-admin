<?php

use common\models\Category;
use common\models\Places\Place;
use common\models\Tag;
use himiklab\sortablegrid\SortableGridView;
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

$gridClass = $searchModel->categories_field ? SortableGridView::class : GridView::class;
?>

<div class="place-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить место', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= $gridClass::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-striped table-bordered table-sm'],
        'columns' => [
            [
                'attribute' => 'place_id',
                'headerOptions' => ['style' => 'width: 75px;'],
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function(Place $model) {
                    return Html::a($model->title, ['places/update', 'id' => $model->place_id]);
                },
            ],
            //'description:ntext',
//            [
//                'attribute' => 'category_id',
//                'format' => 'raw',
//                'value' => function(Place $model) {
//                    return $model->category ? Html::a($model->category->title, ['categories/view', 'id' => $model->category_id]) : '-';
//                },
//                'filter' => Html::activeDropDownList($searchModel, 'category_id', ArrayHelper::map(Category::find()->orderBy('title ASC')->all(), 'category_id', 'title'), ['class' => 'form-control', 'prompt' => '']),
//            ],
            [
                'attribute' => 'categories_field',
                'format' => 'raw',
                'value' => function(Place $model) {
                    return $model->getCategoriesLabels();
                },
                'filter' => Html::activeDropDownList($searchModel, 'categories_field', ArrayHelper::map(Category::find()->orderBy('title ASC')->all(), 'category_id', 'title'), ['class' => 'form-control', 'prompt' => '']),
            ],
            [
                'attribute' => 'tags_field',
                'format' => 'raw',
                'value' => function(Place $model) {
                    return $model->getTagsLabels();
                },
                'filter' => Html::activeDropDownList($searchModel, 'tags_field', ArrayHelper::map(Tag::find()->orderBy('title ASC')->all(), 'tag_id', 'title'), ['class' => 'form-control', 'prompt' => '']),
            ],
            //'in_trash',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
                'headerOptions' => ['style' => 'width: 220px;'],
            ],
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Place $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->place_id]);
                 }
            ],
        ],
    ]); ?>
</div>
