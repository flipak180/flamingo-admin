<?php

namespace api\models\Stories;

use api\models\ApiItem;
use common\models\Stories\Story;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class StoryApiItem implements ApiItem
{

    /**
     * @param Story $model
     * @param array $extra
     * @return array
     */
    public static function from($model, array $extra = []): array
    {

        $image = $model->image
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$model->image->path, 480, 1000, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

        return [
            'id' => $model->id,
            'title' => $model->title,
            'image' => $image,
        ];
    }

}
