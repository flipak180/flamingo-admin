<?php

namespace common\models\Places;

use common\base\DataTransferObject;
use common\models\User;
use common\models\UserPlace;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class PlaceApiItem extends DataTransferObject
{
    public $id;
    public $title;
    public $sort_title;
    public $image;
    public $images;
    public $small_images;
    public $small_image;
    public $big_images;
    public $big_image;
    public $tags;
    public $coords;
    public $user_status;
    public $stats;
    public $position;

    /**
     * @param Place $model
     * @return PlaceApiItem
     */
    public static function from($model): PlaceApiItem
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

        $dto = new self();
        $dto->id = $model->place_id;
        $dto->title = $model->title;
        $dto->sort_title = $model->sort_title;
        $dto->image = count($images) ? $images[0] : '';
        $dto->images = $images;
        $dto->small_image = count($smallImages) ? $smallImages[0] : '';
        $dto->small_images = $smallImages;
        $dto->big_image = count($bigImages) ? $bigImages[0] : '';
        $dto->big_images = $bigImages;
        $dto->tags = $tags;
        $dto->coords = $model->coords;
        $dto->user_status = $userPlace ? $userPlace->status : 0;
        $dto->stats = $model->getStats();
        $dto->position = $model->position;

        return $dto;
    }

}
