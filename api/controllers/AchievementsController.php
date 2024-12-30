<?php

namespace api\controllers;

use common\models\Achievements\AchievementCategory;
use common\models\Achievements\AchievementProgress;
use common\models\Achievements\AchievementsSearch;
use OpenApi\Attributes as OA;
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

    #[OA\Post(path: '/api/achievements/list-by-categories', tags: ['achievements'])]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionListByCategories()
    {
        $data = AchievementsSearch::getListByCategories();
        return $this->response($data);
    }

    #[OA\Post(path: '/api/achievements/list', tags: ['achievements'])]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionList()
    {
        $data = AchievementsSearch::getList();
        return $this->response($data);
    }

    #[OA\Post(path: '/api/achievements/categories', tags: ['achievements'])]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionCategories()
    {
        $data = AchievementCategory::getList();
        return $this->response($data);
    }

    #[OA\Post(
        path: '/api/achievements/add-progress',
        tags: ['achievements'],
        parameters: [
            new OA\Parameter(name: 'achievement_id', description: 'ID of the achievement', in: 'query', required: true),
            new OA\Parameter(name: 'points', description: 'Points of the achievement', in: 'query'),
        ],
    )]
    #[OA\Response(
        response: '200',
        description: 'OK',
        content: new OA\JsonContent()
    )]
    public function actionAddProgress()
    {
        $achievement_id = Yii::$app->request->post('achievement_id');
        $points = Yii::$app->request->post('points', 1);

        $data = AchievementProgress::addProgress($achievement_id, $points);
        return $this->response($data);
    }

}
