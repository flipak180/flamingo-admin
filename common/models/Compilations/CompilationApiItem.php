<?php

namespace common\models\Compilations;

use common\base\DataTransferObject;
use common\models\Places\PlaceApiItem;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class CompilationApiItem extends DataTransferObject
{
    public $id;
    public $title;
    public $description;
    public $image;
    public $total_places;
    public $places;

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
        $dto->description = $model->description;
        $dto->image = $image;
        $dto->total_places = count($model->places);
        $dto->places = $dto->getPlaces($model);
        return $dto;
    }

    /**
     * @param Compilation $model
     * @return array
     */
    private function getPlaces($model)
    {
        $result = [];
        foreach ($model->places as $place) {
            $result[] = PlaceApiItem::from($place)->attributes;
        }
        return $result;
    }
}
