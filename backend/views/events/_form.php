<?php

use common\models\Place;
use himiklab\thumbnail\EasyThumbnailImage;
use kartik\editors\Summernote;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Event $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="event-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'images_field')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple' => true],
        'pluginOptions' => [
            'browseLabel' => 'Выбрать',
            'showPreview' => false,
            'showUpload' => false,
            'showRemove' => false,
        ]
    ]); ?>

    <?php if (count($model->images)): ?>
        <div class="image-preview__cont">
            <?php foreach ($model->images as $image): ?>
                <div class="image-preview">
                    <a href="<?= $image->path ?>" target="_blank">
                        <?= EasyThumbnailImage::thumbnailImg(Yii::getAlias('@frontend_web').$image->path, 250, 250) ?>
                    </a>
                    <div class="actions">
                        <?= Html::a('<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg>',
                            ['site/delete-image', 'id' => $image->image_id], ['class' => 'btn btn-xs btn-danger']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif ?>

    <?= $form->field($model, 'place_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Place::find()->all(), 'place_id', 'title'),
        'options' => ['placeholder' => 'Выберите место'],
    ]); ?>
    <?= $form->field($model, 'description')->widget(Summernote::class, [
        'useKrajeePresets' => true,
        // other widget settings
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
