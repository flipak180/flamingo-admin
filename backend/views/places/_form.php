<?php

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
?>

<div class="place-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
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

    <?= $form->field($model, 'location_field')->hiddenInput() ?>
    <div id="place-map"></div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
