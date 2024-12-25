<?php

namespace common\models\Stories;

use common\base\DataTransferObject;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class StoryApiItem extends DataTransferObject
{
    public $id;
    public $title;
    public $image;

    /**
     * @param Story $model
     * @return StoryApiItem
     */
    public function from($model): StoryApiItem
    {

        $image = $model->image
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$model->image->path, 480, 1000, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

        $dto = new self();
        $dto->id = $model->id;
        $dto->title = $model->title;
        $dto->image = $image;

        return $dto;
    }

}
