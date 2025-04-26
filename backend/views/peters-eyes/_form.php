<?php

use common\models\PetersEye;
use himiklab\thumbnail\EasyThumbnailImage;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PetersEye $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="peters-eye-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'image_field')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'browseLabel' => 'Выбрать',
            'showPreview' => false,
            'showUpload' => false,
            'showRemove' => false,
        ]
    ]); ?>
    <?php if ($model->image): ?>
        <div class="image-preview">
            <a href="<?= $model->image->path ?>" target="_blank">
                <?= EasyThumbnailImage::thumbnailImg(Yii::getAlias('@frontend_web').$model->image->path, 100, 100) ?>
            </a>
            <p><?= Html::a('Удалить', ['site/delete-image', 'id' => $model->image->image_id], ['class' => 'btn btn-xs btn-danger']) ?></p>
        </div>
    <?php endif ?>
    <?= $form->field($model, 'coords_field')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'prize')->textInput() ?>
    <?= $form->field($model, 'status')->dropDownList(PetersEye::getStatusesList()) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
