<?php

use common\models\Category;
use common\models\Tag;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Place $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJsFile(
    'https://api-maps.yandex.ru/2.1/?apikey=048d2b9a-9e4a-481c-9799-c8f42c0ce65a&lang=ru_RU',
    ['position' => View::POS_HEAD]
);
$this->registerJsFile(
    '/admin/js/map.js',
    ['position' => View::POS_HEAD]
);
?>

<div class="place-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <!--
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    -->
    <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Category::find()->all(), 'category_id', 'title'),
        'options' => ['placeholder' => 'Выберите категорию'],
    ]); ?>
    <?= $form->field($model, 'tags_field')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Tag::find()->all(), 'tag_id', 'title'),
        'options' => ['placeholder' => 'Выберите теги', 'multiple' => true],
        'showToggleAll' => false,
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [',', ' '],
            'maximumInputLength' => 10
        ],
    ]); ?>

    <?= $form->field($model, 'location_field')->hiddenInput() ?>
    <div class="place-map-wrap">
        <div id="place-map"></div>
        <div class="buttons">
            <?= Html::button('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
              <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"></path>
            </svg>', ['class' => 'btn btn-sm btn-primary btn-edit-polygon', 'title' => 'Редактировать']) ?>
                <?= Html::button('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
            </svg>', ['class' => 'btn btn-sm btn-danger btn-clear-polygon', 'title' => 'Очистить']) ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?php if (!$model->isNewRecord): ?>
            <?= Html::a('+ Место', ['create'], ['class' => 'btn btn-primary']) ?>
            <?php if ($model->category): ?>
                <?= Html::a('+ ' . $model->category->title, ['create', 'category_id' => $model->category_id], ['class' => 'btn btn-secondary']) ?>
            <?php endif ?>
            <?php foreach ($model->tags as $tag): ?>
                <?= Html::a('+ ' . $tag->title, ['create', 'tag_id' => $tag->tag_id], ['class' => 'btn btn-secondary']) ?>
            <?php endforeach; ?>
        <?php endif ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
