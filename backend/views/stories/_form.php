<?php

use common\models\Stories\Story;
use himiklab\thumbnail\EasyThumbnailImage;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \common\models\Stories\Story $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="story-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
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
    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList(Story::getStatusesList()) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
