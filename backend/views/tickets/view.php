<?php

use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Ticket $model */

$this->title = 'Тикет #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Тикеты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => $model->getTypeTitle(),
            ],
            'message:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <?php if (count($model->images)): ?>
        <div class="image-preview__cont">
            <?php foreach ($model->images as $image): ?>
                <div class="image-preview">
                    <a href="<?= $image->path ?>" target="_blank">
                        <?= EasyThumbnailImage::thumbnailImg(Yii::getAlias('@frontend_web').$image->path, 250, 250) ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif ?>

</div>
