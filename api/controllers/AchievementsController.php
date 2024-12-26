<?php

namespace app\controllers;

use common\models\Achievements\AchievementCategory;
use common\models\Achievements\AchievementProgress;
use common\models\Achievements\AchievementsSearch;
use Yii;
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
            'only' => ['list-by-categories', 'list', 'categories', 'add-progress'],
            'optional' => ['list-by-categories', 'list', 'categories'],
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function actionListByCategories()
    {
        $data = AchievementsSearch::getListByCategories();
        return $this->response($data);
    }

    /**
     * @return array
     */
    public function actionList()
    {
        $data = AchievementsSearch::getList();
        return $this->response($data);
    }

    /**
     * @return array
     */
    public function actionCategories()
    {
        $data = AchievementCategory::getList();
        return $this->response($data);
    }

    /**
     * @return array
     */
    public function actionAddProgress()
    {
        $achievement_id = Yii::$app->request->post('achievement_id');
        $points = Yii::$app->request->post('points', 1);

        $data = AchievementProgress::addProgress($achievement_id, $points);
        return $this->response($data);
    }

}
