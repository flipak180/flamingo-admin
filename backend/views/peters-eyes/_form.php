<?php

use common\models\PetersEye;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PetersEye $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="peters-eye-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'coords_field')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'prize')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList(PetersEye::getStatusesList()) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
