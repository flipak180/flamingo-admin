<?php

namespace common\models\Categories;

use common\base\DataTransferObject;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class CategoryApiItem extends DataTransferObject
{
    public $id;
    public $title;
    public $tags;
    public $image;
    public $smallImage;
    public $places;

    /**
     * @param Category $model
     * @return CategoryApiItem
     */
    public function from($model): CategoryApiItem
    {
        $dto = new self();

        $smallImage = $model->image
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$model->image, 100, 82, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

        $dto->id = $model->category_id;
        $dto->title = $model->title;
        $dto->tags = $dto->getTags($model);
        $dto->image = $model->image;
        $dto->smallImage = $smallImage;
        $dto->places = $dto->getPlaces($model);
        return $dto;
    }

    /**
     * @param $model
     * @return array
     */
    private function getTags($model) {
        $tags = [];
        if ($model->isRelationPopulated('tags')) {
            \Yii::info("Found relation populated category api items");
            foreach ($model->tags as $tag) {
                $tags[] = [
                    'id' => $tag->tag_id,
                    'title' => $tag->title,
                ];
            }
        }
        return $tags;
    }

    /**
     * @param $model
     * @return array
     */
    private function getPlaces($model) {
        $places = [];
//        if ($model->isRelationPopulated('tags')) {
//            \Yii::info("Found relation populated category api items");
//            foreach ($model->tags as $tag) {
//                $places[] = [
//                    'id' => $tag->tag_id,
//                    'title' => $tag->title,
//                ];
//            }
//        }
        return $places;
    }

}
