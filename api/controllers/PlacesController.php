<?php

namespace api\controllers;

use api\models\Places\PlaceApiItem;
use common\models\PlaceRate;
use common\models\Places\Place;
use common\models\Places\PlacesSearch;
use common\models\Visit;
use Yii;
use yii\filters\auth\HttpBearerAuth;

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
            'only' => ['rate', 'list', 'search', 'details', 'visit'],
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
            $result[] = PlaceApiItem::from($place);
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
            $result[] = PlaceApiItem::from($place);
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
        return PlaceApiItem::from($place);
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
     */
    public function actionVisit()
    {
        $place_id = Yii::$app->request->post('place_id');
        return Visit::create($place_id);
    }
}
