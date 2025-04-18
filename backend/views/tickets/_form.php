<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Ticket $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="ticket-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'user_id')->textInput() ?>
    <?= $form->field($model, 'type')->textInput() ?>
    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
