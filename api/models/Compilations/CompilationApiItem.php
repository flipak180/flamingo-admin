<?php

namespace api\models\Compilations;

use api\models\ApiItem;
use api\models\Places\PlaceApiItem;
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
        $image = $model->image
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$model->image->path, 720, 400, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

        $places = [];
        foreach ($model->places as $place) {
            $places[] = PlaceApiItem::from($place);
        }

        return [
            'id' => $model->compilation_id,
            'title' => $model->title,
            'description' => $model->description,
            'image' => $image,
            'total_places' => count($model->places),
            'places' => $places,
        ];
    }
}
