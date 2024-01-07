<?php

namespace app\controllers;

use common\models\Category;
use common\models\Place;
use common\models\User;
use common\models\Visit;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class PlacesController extends BaseApiController
{
    /**
     * @param $category_id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionList($category_id = null)
    {
//        $result = [];
//
//        /** @var Place[] $places */
//        $places = Place::find()
//            ->where('in_trash IS NOT TRUE')
//            ->orderBy('place_id DESC')
//            ->limit(5)
//            //->orderBy(new Expression('RANDOM()'))
//            ->all();
//
//        foreach ($places as $place) {
//            $images = [];
//            foreach ($place->images as $image) {
//                // $images[] = $image->path;
//                $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 500, 800, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
//            }
//
//            $tags = [];
//            foreach ($place->tags as $tag) {
//                $tags[] = $tag->title;
//            }
//
//            $result[] = [
//                'id' => $place->place_id,
//                'title' => $place->title,
//                'image' => count($images) ? $images[0] : '',
//                'images' => $images,
//                'tags' => $tags,
//                'coords' => $place->coords,
//            ];
//        }
//
//        return $result;


        $result = [];
        $tagIds = [];

        $category = Category::findOne($category_id);
        if ($category) {
            foreach ($category->categoryTags as $categoryTag) {
                $tagIds[] = $categoryTag->tag_id;
            }
        }

        //$orderDir = ($category->type == Category::TYPE_CATALOG) ? 'DESC' : 'ASC';

        /** @var Place[] $places */
        $places = Place::find()->joinWith(['placeTags', 'placeCategories'])
            ->where('in_trash IS NOT TRUE')
            ->orWhere(['places.category_id' => $category_id])
            ->orWhere(['place_categories.category_id' => $category_id])
            ->orWhere(['in', 'tag_id', $tagIds])
            //->orderBy('place_id ' . $orderDir)
            ->limit(20)
            ->all();

        foreach ($places as $place) {
            $images = [];
            foreach ($place->images as $image) {
                // $images[] = $image->path;
                $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 500, 800, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
            }

            $tags = [];
            foreach ($place->tags as $tag) {
                $tags[] = $tag->title;
            }

            $result[] = [
                'id' => $place->place_id,
                'title' => $place->title,
                'image' => count($images) ? $images[0] : '',
                'images' => $images,
                'tags' => $tags,
                'coords' => $place->coords,
            ];
        }

        return $result;

//        return Place::find()->joinWith('placeTags')
//            ->where(['category_id' => $category_id])
//            ->orWhere(['in', 'tag_id', $tagIds])
//            //->orderBy('place_id ' . $orderDir)
//            ->limit(20)
//            ->all();
    }

    /**
     * @param $id
     * @return array
     */
    public function actionDetails($id)
    {
        $place = Place::findOne($id);

        return [
            'id' => $place->place_id,
            'title' => $place->title,
            'description' => $place->description,
        ];
    }

    /**
     * @return bool
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionVisit()
    {
        $params = Yii::$app->request->getBodyParams();
        if (!isset($params['place_id']) || !isset($params['phone'])) {
            throw new BadRequestHttpException('Некорректные данные');
        }

        $user = User::findOne(['phone' => $params['phone']]);
        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        $place = Place::findOne($params['place_id']);
        if (!$place) {
            throw new NotFoundHttpException('Место не найдено');
        }

        $visit = new Visit();
        $visit->place_id = $place->place_id;
        $visit->user_id = $user->user_id;
        if (!$visit->validate()) {
            throw new BadRequestHttpException('Вы уже отметились');
        }

        return $visit->save();
    }
}
