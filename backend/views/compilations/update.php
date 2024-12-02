<?php

use common\models\Places\Place;
use yii\data\ActiveDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var \common\models\Compilations\Compilation $model */

$this->title = 'Редактирование подборки: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Подборки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'compilation_id' => $model->compilation_id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="compilation-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

<?= GridView::widget([
    'dataProvider' => new ActiveDataProvider([
        'query' => $model->getPlaces()
    ]),
    'columns' => [
        [
            'attribute' => 'place_id',
            'headerOptions' => ['style' => 'width: 75px;'],
        ],
        'title',
        [
            'attribute' => 'categories_field',
            'format' => 'raw',
            'value' => function(Place $model) {
                return $model->getCategoriesLabels();
            },
        ],
        [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, Place $model, $key, $index, $column) {
                return Url::toRoute([$action, 'id' => $model->place_id]);
            },
            'template' => '{update}'
        ],
    ],
]); ?>
