<?php

namespace api\controllers;

use common\models\UserCategory;
use OpenApi\Attributes as OA;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class UserCategoriesController extends BaseApiController
{
    public $modelClass = 'common\models\UserCategory';

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    #[OA\Post(
        path: '/api/user-categories/toggle-category',
        tags: ['user-categories'],
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionToggleCategory()
    {
        $categoryId = Yii::$app->request->post('category_id');
        return UserCategory::toggleCategory($categoryId, Yii::$app->user->id);
    }

    #[OA\Post(
        path: '/api/user-categories/toggle-category',
        tags: ['user-categories'],
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionGetCategories()
    {
        return UserCategory::getCategoryIdsByUserId(Yii::$app->user->id);
    }
}
