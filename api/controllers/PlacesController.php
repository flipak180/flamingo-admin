<?php

namespace app\controllers;

use common\models\PlaceRate;
use common\models\Places\Place;
use common\models\Places\PlaceApiItem;
use common\models\Places\PlacesSearch;
use common\models\User;
use common\models\Visit;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class PlacesController extends BaseApiController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['rate', 'list', 'search', 'details'],
            'optional' => ['list', 'search', 'details'],
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function actionList()
    {
        $category_id = Yii::$app->request->post('category_id');
        $tag_id = Yii::$app->request->post('tag_id');

        $result = [];
        $places = PlacesSearch::getByCategory($category_id, $tag_id);
        foreach ($places as $place) {
            $result[] = PlaceApiItem::from($place)->attributes;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function actionSearch()
    {
        $term = Yii::$app->request->post('term');

        $result = [];
        $places = PlacesSearch::getByTerm($term);
        foreach ($places as $place) {
            $result[] = PlaceApiItem::from($place)->attributes;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function actionDetails()
    {
        $id = Yii::$app->request->post('id');

        /** @var Place $place */
        $place = Place::findOne($id);
        return PlaceApiItem::from($place)->attributes;
    }

    /**
     * @return bool
     */
    public function actionRate()
    {
        $rate = Yii::$app->request->post('rate');
        $place_id = Yii::$app->request->post('place_id');

        return PlaceRate::create($place_id, $rate);
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
