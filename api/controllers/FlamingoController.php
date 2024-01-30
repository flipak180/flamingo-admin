<?php

namespace app\controllers;

use common\models\Place;
use Yii;

class FlamingoController extends BaseApiController
{

    public function actionSearch()
    {
        $result = [];

        $term = Yii::$app->request->get('term');
        if (!$term) {
            return $result;
        }

        /** @var Place[] $places */
        $places = Place::find()->andFilterWhere([
            'or',
            ['ilike', 'title', $term],
            ['ilike', 'description', $term],
        ])->orderBy('title ASC')->all();

        foreach ($places as $place) {
            $result[] = [
                'id' => $place->place_id,
                'title' => $place->title,
            ];
        }

        return $result;
    }

}
