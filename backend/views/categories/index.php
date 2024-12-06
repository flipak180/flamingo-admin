<?php

use common\models\Categories\Category;
use common\models\Tag;
use himiklab\sortablegrid\SortableGridView;
use yii\grid\ActionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

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
        'tableOptions' => ['class' => 'table table-striped table-bordered table-sm'],
        'rowOptions' => function(Category $model) {
            $style = '';
            if (!$model->parent_id) {
                $style .= 'background-color: #ccc;';
            }
            if ($model->in_trash) {
                $style .= 'opacity: .7; text-decoration: line-through;';
            }
            return ['style' => $style];
        },
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
                'buttons' => [
                    'toggle' => function ($url, Category $model, $key) {
                        return $model->in_trash
                            ? Html::a('<img src="/upload/icons/eye.svg" alt="Bootstrap" width="16" height="16">', $url, ['title' => 'Отобразить'])
                            : Html::a('<img src="/upload/icons/eye-slash.svg" alt="Bootstrap" width="16" height="16">', $url, ['title' => 'Скрыть']);
                    },
                ],
//                'urlCreator' => function ($action, Category $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->category_id]);
//                },
                'template' => '{toggle}'
            ],
        ],
    ]); ?>
</div>
