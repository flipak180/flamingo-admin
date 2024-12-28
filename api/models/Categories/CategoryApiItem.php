<?php

namespace api\models\Categories;

use ApiItem;
use common\models\Categories\Category;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class CategoryApiItem implements ApiItem
{

    /**
     * @param Category $model
     * @param array $extra
     * @return array
     */
    public static function from($model, array $extra = []): array
    {
        $dto = new self();

        $smallImage = $model->image
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$model->image, 100, 82, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

        return [
            'id' => $model->category_id,
            'title' => $model->title,
            'tags' => $dto->getTags($model),
            'image' => $model->image,
            'smallImage' => $smallImage,
            'places' => $dto->getPlaces($model),
        ];
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
