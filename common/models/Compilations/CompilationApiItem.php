<?php

namespace common\models\Compilations;

use common\base\DataTransferObject;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class CompilationApiItem extends DataTransferObject
{
    public $id;
    public $title;
    public $image;
    public $total_places;

    /**
     * @param Compilation $model
     * @return CompilationApiItem
     */
    public static function from($model): CompilationApiItem
    {
        $dto = new self();

        $image = $model->image
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$model->image, 720, 400, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

        $dto->id = $model->compilation_id;
        $dto->title = $model->title;
        $dto->image = $image;
        $dto->total_places = count($model->places);
        return $dto;
    }

}
