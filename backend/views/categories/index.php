<?php

use common\models\Category;
use common\models\Tag;
use himiklab\sortablegrid\SortableGridView;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\CategoriesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="category-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= SortableGridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'category_id',
                'headerOptions' => ['style' => 'width: 75px;'],
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function(Category $model) {
                    return Html::a($model->title, ['categories/update', 'id' => $model->category_id]);
                },
            ],
            //'image',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function(Category $model) {
                    return $model->getTypeTitle();
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', Category::getTypesList(), ['class' => 'form-control', 'prompt' => '']),
            ],
            [
                'attribute' => 'parent_id',
                'format' => 'raw',
                'value' => function(Category $model) {
                    return $model->parent ? Html::a($model->parent->title, ['categories/view', 'id' => $model->parent_id]) : '-';
                },
                'filter' => Html::activeDropDownList($searchModel, 'parent_id', ArrayHelper::map(Category::find()->orderBy('title ASC')->all(), 'category_id', 'title'), ['class' => 'form-control', 'prompt' => '']),
            ],
            [
                'attribute' => 'tags_field',
                'format' => 'raw',
                'value' => function(Category $model) {
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
                'urlCreator' => function ($action, Category $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->category_id]);
                },
                'template' => '{delete}'
            ],
        ],
    ]); ?>
</div>
