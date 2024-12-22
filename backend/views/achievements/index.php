<?php

use common\models\Achievements\Achievement;
use common\models\Achievements\AchievementCategory;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\models\AchievementsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Достижения';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="achievement-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Добавить достижение', ['create'], ['class' => 'btn btn-success']) ?>
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
                'value' => function(Achievement $model) {
                    return Html::a($model->title, ['achievements/update', 'id' => $model->id]);
                },
            ],
            'description',
            [
                'attribute' => 'category_id',
                'format' => 'raw',
                'value' => function(Achievement $model) {
                    return $model->category->title;
                },
                'filter' => Html::activeDropDownList($searchModel, 'category_id', ArrayHelper::map(AchievementCategory::find()->orderBy('title ASC')->all(), 'id', 'title'), ['class' => 'form-control', 'prompt' => '']),
            ],
            //'level',
            //'points',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function(Achievement $model) {
                    return $model->getStatusName();
                },
                'filter' => Html::activeDropDownList($searchModel, 'status', Achievement::getStatusesList(), ['class' => 'form-control', 'prompt' => '']),
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false,
                'headerOptions' => ['style' => 'width: 220px;'],
            ],
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Achievement $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
</div>
