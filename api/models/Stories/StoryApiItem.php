<?php

namespace api\models\Stories;

use common\models\Stories\Story;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class StoryApiItem
{

    /**
     * @param Story $model
     * @return array
     */
    public static function from(Story $model): array
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
