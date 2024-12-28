<?php

namespace api\models\Places;

use api\models\ApiItem;
use common\models\Places\Place;
use common\models\User;
use common\models\UserPlace;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class PlaceApiItem implements ApiItem
{

    /**
     * @param Place $model
     * @param array $extra
     * @return array
     */
    public static function from($model, array $extra = []): array
    {
        $images = [];
        $bigImages = [];
        $smallImages = [];
        foreach ($model->images as $image) {
            $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 344, 344, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
            $bigImages[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 716, 600, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
            $smallImages[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 150, 150, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
        }

        $tags = [];
        foreach ($model->tags as $tag) {
            $tags[] = $tag->title;
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;
        $userPlace = $user ? UserPlace::findOne(['place_id' => $model->place_id, 'user_id' => $user->user_id]) : null;

        return [
            'id' => $model->place_id,
            'title' => $model->title,
            'sort_title' => $model->sort_title,
            'image' => count($images) ? $images[0] : '',
            'images' => $images,
            'small_image' => count($smallImages) ? $smallImages[0] : '',
            'small_images' => $smallImages,
            'big_image' => count($bigImages) ? $bigImages[0] : '',
            'big_images' => $bigImages,
            'tags' => $tags,
            'coords' => $model->coords,
            'user_status' => $userPlace ? $userPlace->status : 0,
            'stats' => $model->getStats(),
            'position' => $model->position,
        ];
    }

}
