<?php

namespace api\models\Categories;

use api\models\ApiItem;
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
        $smallImage = $model->image
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$model->image, 100, 82, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

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

        return [
            'id' => $model->category_id,
            'title' => $model->title,
            'tags' => $tags,
            'image' => $model->image,
            'smallImage' => $smallImage,
            'places' => [],
        ];
    }

}
