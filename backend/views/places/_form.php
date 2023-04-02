<?php

use common\models\Category;
use common\models\Tag;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Place $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="place-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'category_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Category::find()->orderBy('title ASC')->all(), 'id', 'title'),
        'options' => ['placeholder' => 'Выберите категорию'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    <?= $form->field($model, 'tags_field')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Tag::find()->all(), 'id', 'title'),
        'options' => ['placeholder' => 'Выберите теги', 'multiple' => true],
        'showToggleAll' => false,
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [',', ' '],
            'maximumInputLength' => 10
        ],
    ]); ?>
    <?= $form->field($model, 'coords_field')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'radius')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
