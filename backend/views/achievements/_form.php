<?php

use common\models\Achievements\Achievement;
use common\models\Achievements\AchievementCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \common\models\Achievements\Achievement $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="achievement-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'category_id')->dropDownList(
        ArrayHelper::map(AchievementCategory::find()->orderBy('title ASC')->all(), 'id', 'title'),
        ['prompt' => '[Не выбрана]']
    ) ?>
    <?= $form->field($model, 'level')->textInput() ?>
    <?= $form->field($model, 'points')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList(Achievement::getStatusesList()) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
