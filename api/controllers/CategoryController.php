<?php

namespace app\controllers;

use common\models\Category;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;
use yii\db\Expression;

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

    /**
     * @return array|null
     * @throws \himiklab\thumbnail\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionGetHomepageCategory()
    {
        /** @var Category $category */
        $category = Category::find()
            ->where(['show_on_homepage' => true])
            ->orderBy(new Expression('random()'))
            ->one();

        if (!$category) {
            return null;
        }

        $places = [];

        $categoryPlaces = $category->places;
        shuffle($categoryPlaces);
        $categoryPlaces = array_slice($categoryPlaces, 0, 5);
        foreach ($categoryPlaces as $place) {
            $image = count($place->images)
                ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$place->images[0]->path, 400, 400, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
                : '';
            $places[] = [
                'id' => $place->place_id,
                'title' => $place->title,
                'image' => $image,
            ];
        }

        return [
            'id' => $category->category_id,
            'title' => $category->title,
            'places' => $places,
        ];
    }

    /**
     * @return array
     */
    public function actionGetPopularCategories()
    {
        /** @var Category[] $categories */
        $categories = Category::find()
            ->where(['is_popular' => true])
            ->orderBy('title')
            ->all();

        $result = [];

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->category_id,
                'title' => $category->title,
                'total_places' => count($category->getPlaces()),
            ];
        }

        return $result;
    }
}
