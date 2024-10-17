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
            $image = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$category->image, 736, 600, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
            $result[] = [
                'id' => $category->category_id,
                'title' => $category->title,
                'image' => $image,
            ];
        }

        return $result;
    }

    /**
     * @param $id
     * @return Category|null
     */
    public function actionDetails($id)
    {
        return Category::findOne($id);
    }
}
