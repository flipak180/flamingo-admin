<?php

namespace api\controllers;

use api\models\Categories\CategoryApiItem;
use common\models\Categories\Category;
use himiklab\thumbnail\EasyThumbnailImage;
use OpenApi\Attributes as OA;
use Yii;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class CategoryController extends BaseApiController
{
    public $modelClass = 'common\models\Categories\Category';

    #[OA\Get(
        path: '/api/categories/list',
        tags: ['categories'],
        parameters: [
            new OA\Parameter(name: 'parent_id', description: 'Parent ID', in: 'path'),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
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
            $result[$category->position] = ArrayHelper::merge(CategoryApiItem::from($category), [
                'subcategories' => [],
            ]);
            foreach ($subcategories as $subcategory) {
                $result[$category->position]['subcategories'][] = CategoryApiItem::from($subcategory);
            }
        }

        //ksort($result);

        return array_values($result);
    }

    #[OA\Get(
        path: '/api/categories/details',
        tags: ['categories'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionDetails($id)
    {
        /** @var Category $category */
        $category = Category::find()->where(['category_id' => $id])->with('tags')->one();
        if (!$category) {
            return null;
        }

        return CategoryApiItem::from($category);
    }

    #[OA\Get(path: '/api/categories/get-homepage-category', tags: ['categories'])]
    #[OA\Response(response: '200', description: 'OK')]
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

    #[OA\Get(path: '/api/categories/get-popular-categories', tags: ['categories'])]
    #[OA\Response(response: '200', description: 'OK')]
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
            $smallImage = $category->image
                ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$category->image, 100, 82, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
                : null;
            $result[] = [
                'id' => $category->category_id,
                'title' => $category->title,
                'total_places' => $category->getCountPlaces(),
                'smallImage' => $smallImage,
            ];
        }

        return $result;
    }
}
