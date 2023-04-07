<?php

namespace app\controllers;

use common\models\Visit;

class PlaceController extends BaseApiController
{
    public $modelClass = 'common\models\Place';

    /**
     * @param $place_id
     * @return mixed
     */
    public function actionVisit($place_id)
    {
        $visit = new Visit();
        $visit->place_id = $place_id;
        $visit->user_id = 1;
        $visit->save();

        return $place_id;
    }
}
