<?php

namespace app\controllers;

use common\models\Category;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class CategoryController extends BaseApiController
{
    public $modelClass = 'common\models\Category';

    /**
     * @param $parent_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionList($parent_id = null)
    {
        $result = [];

        /** @var Category[] $categories */
        $categories = Category::find()->where(['parent_id' => $parent_id])->orderBy('position ASC')->all();
        foreach ($categories as $category) {
            /** @var Category[] $subcategories */
            $subcategories = Category::find()->where(['parent_id' => $category->category_id])->orderBy('position ASC')->all();
            $result[$category->position] = [
                'id' => $category->category_id,
                'title' => $category->title,
                'subcategories' => [],
            ];
            foreach ($subcategories as $subcategory) {
                $image = $subcategory->image
                    ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$subcategory->image, 736, 600, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
                    : null;
                $result[$category->position]['subcategories'][] = [
                    'id' => $subcategory->category_id,
                    'title' => $subcategory->title,
                    'image' => $image,
                ];
            }
        }

        //ksort($result);

        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public function actionDetails($id)
    {
        $category = Category::findOne($id);
        $tags = [];
        foreach ($category->tags as $tag) {
            $tags[] = [
                'id' => $tag->tag_id,
                'title' => $tag->title,
            ];
        }

        return [
            'id' => $category->category_id,
            'title' => $category->title,
            'tags' => $tags,
        ];
    }
}
