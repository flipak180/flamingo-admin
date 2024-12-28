<?php

namespace api\models\Compilations;

use api\models\ApiItem;
use common\models\Compilations\Compilation;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class CompilationApiItem implements ApiItem
{

    /**
     * @param Compilation $model
     * @param array $extra
     * @return array
     */
    public static function from($model, array $extra = []): array
    {
        $dto = new self();

        $image = $model->image
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$model->image->path, 720, 400, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

        return [
            'id' => $model->compilation_id,
            'title' => $model->title,
            'description' => $model->description,
            'image' => $image,
            'total_places' => count($model->places),
            'places' => $dto->getPlaces($model),
        ];
    }

    /**
     * @param Compilation $model
     * @return array
     */
    private function getPlaces($model)
    {
        $result = [];
        foreach ($model->places as $place) {
            $result[] = PlaceApiItem::create()->from($place)->attributes;
        }
        return $result;
    }
}
