<?php

namespace app\controllers;

use common\models\Categories\Category;
use common\models\Categories\CategoryApiItem;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;
use yii\db\Expression;

class CategoryController extends BaseApiController
{
    public $modelClass = 'common\models\Categories\Category';

    /**
     * @param $parent_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionList($parent_id = null)
    {
        $result = [];

        /** @var Category[] $categories */
        $categories = Category::find()
            ->where(['parent_id' => $parent_id])
            ->andWhere('categories.in_trash IS NOT TRUE')
            ->orderBy('position ASC')
            ->all();
        foreach ($categories as $category) {
            /** @var Category[] $subcategories */
            $subcategories = Category::find()
                ->where(['parent_id' => $category->category_id])
                ->andWhere('categories.in_trash IS NOT TRUE')
                ->orderBy('position ASC')
                ->all();
            $result[$category->position] = [
                'id' => $category->category_id,
                'title' => $category->title,
                'subcategories' => [],
            ];
            foreach ($subcategories as $subcategory) {
                $image = $subcategory->image
                    ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$subcategory->image, 100, 82, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
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
     * @return array|null
     */
    public function actionDetails($id)
    {
        /** @var Category $category */
        $category = Category::find()->where(['category_id' => $id])->with('tags')->one();
        if (!$category) {
            return null;
        }

        return CategoryApiItem::create()->from($category)->attributes;
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
            ->andWhere('in_trash IS NOT TRUE')
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
                ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$place->images[0]->path, 344, 344, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
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
            ->andWhere('in_trash IS NOT TRUE')
            ->orderBy('title')
            ->all();

        $result = [];

        foreach ($categories as $category) {
            $result[] = [
                'id' => $category->category_id,
                'title' => $category->title,
                'total_places' => $category->getCountPlaces(),
            ];
        }

        return $result;
    }
}
