<?php

namespace api\controllers;

use api\models\Places\PlaceApiItem;
use common\models\PlaceRate;
use common\models\Places\Place;
use common\models\Places\PlacesSearch;
use common\models\Visit;
use OpenApi\Attributes as OA;
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

    #[OA\Post(
        path: '/api/places/list',
        tags: ['places'],
        parameters: [
            new OA\Parameter(name: 'category_id', description: 'Category ID', in: 'query'),
            new OA\Parameter(name: 'tag_id', description: 'Tag ID', in: 'query'),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
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

    #[OA\Post(
        path: '/api/places/search',
        tags: ['places'],
        parameters: [
            new OA\Parameter(name: 'term', description: 'Search Term', in: 'query'),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
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

    #[OA\Post(
        path: '/api/places/details',
        tags: ['places'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionDetails()
    {
        $id = Yii::$app->request->post('id');

        /** @var Place $place */
        $place = Place::findOne($id);
        return PlaceApiItem::from($place);
    }

    #[OA\Post(
        path: '/api/places/rate',
        tags: ['places'],
        parameters: [
            new OA\Parameter(name: 'rate', description: 'Rate', in: 'query', required: true),
            new OA\Parameter(name: 'place_id', description: 'Place ID', in: 'query', required: true),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionRate()
    {
        $rate = Yii::$app->request->post('rate');
        $place_id = Yii::$app->request->post('place_id');

        return PlaceRate::create($place_id, $rate);
    }

    #[OA\Post(
        path: '/api/places/visit',
        tags: ['places'],
        parameters: [
            new OA\Parameter(name: 'place_id', description: 'Place ID', in: 'query', required: true),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionVisit()
    {
        $place_id = Yii::$app->request->post('place_id');
        return Visit::create($place_id);
    }
}
