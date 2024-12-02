<?php

use common\models\Categories\Category;
use common\models\Tag;
use himiklab\thumbnail\EasyThumbnailImage;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \common\models\Categories\Category $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="category-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'type')->dropDownList(Category::getTypesList()) ?>
    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>
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
            <a href="<?= $model->image ?>" target="_blank">
                <?= EasyThumbnailImage::thumbnailImg(Yii::getAlias('@frontend_web').$model->image, 100, 100) ?>
            </a>
            <p><?= Html::a('Удалить', ['delete-image', 'id' => $model->category_id], ['class' => 'btn btn-xs btn-danger']) ?></p>
        </div>
    <?php endif ?>
    <?= $form->field($model, 'parent_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Category::find()->orderBy('title ASC')->all(), 'category_id', 'title'),
        'options' => ['placeholder' => 'Выберите категорию'],
        'pluginOptions' => [
            'allowClear' => true
        ],
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
    <?= $form->field($model, 'is_popular')->checkbox() ?>
    <?= $form->field($model, 'show_on_homepage')->checkbox() ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
