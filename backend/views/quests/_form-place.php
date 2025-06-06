<?php

use common\models\QuestPlace;
use himiklab\thumbnail\EasyThumbnailImage;
use kartik\editors\Summernote;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\QuestPlace $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJsFile(
    'https://api-maps.yandex.ru/2.1/?apikey=048d2b9a-9e4a-481c-9799-c8f42c0ce65a&lang=ru_RU&suggest_apikey=6c2da75d-2aeb-430a-8cd9-2641ace59812',
    ['position' => View::POS_HEAD]
);
$this->registerJsFile(
    '/admin/js/map.js',
    ['position' => View::POS_HEAD]
);
?>

<div class="quest-place-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'coords_field')->textInput(['maxlength' => true]) ?>

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

    <?= $form->field($model, 'description')->widget(Summernote::class, [
        'useKrajeePresets' => true,
        // other widget settings
    ]) ?>

    <?= $form->field($model, 'quiz_type')->dropDownList(QuestPlace::getQuizTypes(), ['prompt' => '[Нет загадки]']) ?>
    <?= $form->field($model, 'quiz_question')->textarea() ?>
<!--    --><?php //= $form->field($model, 'quiz_answer')->textInput(['maxlength' => true]) ?>

    <div class="quiz_type quiz_type-<?= QuestPlace::QUIZ_TYPE_OPTIONS ?> <?= $model->quiz_type == QuestPlace::QUIZ_TYPE_OPTIONS ? '' : 'hidden' ?>">
        <p>123</p>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php $this->registerJsFile('/admin/js/quest-place.js', ['position' => View::POS_END]); ?>
