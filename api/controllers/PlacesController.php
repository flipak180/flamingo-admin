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
    public function actionList($category_id = null, $tag_id = null)
    {
        $result = [];
        $tagIds = [];

        $category = Category::findOne($category_id);
        if ($category && !$tag_id) {
            foreach ($category->categoryTags as $categoryTag) {
                $tagIds[] = $categoryTag->tag_id;
            }
        } else {
            $tagIds = [$tag_id];
        }

        //$orderDir = ($category->type == Category::TYPE_CATALOG) ? 'DESC' : 'ASC';

        /** @var Place[] $places */
        $places = Place::find()->joinWith(['placeTags', 'placeCategories'])
            ->andWhere('places.in_trash IS NOT TRUE')
            ->andWhere([
                $tag_id ? 'and' : 'or',
                ['place_categories.category_id' => $category_id],
                ['in', 'place_tags.tag_id', $tagIds]
            ])
            ->orderBy('places.title ASC')
            ->limit(20)
            ->all();

        foreach ($places as $place) {
            $images = [];
            foreach ($place->images as $image) {
                // 500x800
                $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 400, 400, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
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
                'status' => 1,
            ];
        }

        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public function actionDetails($id)
    {
        /** @var Place $place */
        $place = Place::findOne($id);

        $images = [];
        foreach ($place->images as $image) {
            $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 716, 600, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
        }

        $tags = [];
        foreach ($place->tags as $tag) {
            $tags[] = $tag->title;
        }

        return [
            'id' => $place->place_id,
            'title' => $place->title,
            'image' => count($images) ? $images[0] : '',
            'images' => $images,
            'description' => $place->description,
            'tags' => $tags,
            'coords' => $place->coords,
            'status' => 1,
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

    public function actionTest()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $params = Yii::$app->request->getBodyParams();
        $messages = $params['data']['messages'];

        $data = [
            "modelUri" => "gpt://b1ge8qr3t2a98df29m34/yandexgpt-lite",
            "completionOptions" => [
                "stream" => false,
                "temperature" => 0.1,
                "maxTokens" => "1000"
            ],
            "messages" => $messages
        ];
        $ch = curl_init('https://llm.api.cloud.yandex.net/foundationModels/v1/completion');
        $payload = json_encode($data);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Api-Key AQVN26Wh1Z9DIBEtfRxiHDq7cWhf0bw7EydI0blq',
            'x-folder-id: b1ge8qr3t2a98df29m34'
        ]);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result);
    }
}
