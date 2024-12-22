<?php

namespace app\controllers;

use common\models\Achievements\AchievementsSearch;
use yii\filters\auth\HttpBearerAuth;

class AchievementsController extends BaseApiController
{
    public $modelClass = 'common\models\Achievements\Achievements';

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['list-by-categories'],
            'optional' => ['list-by-categories'],
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function actionListByCategories()
    {
        return AchievementsSearch::getListByCategories();
    }

}
