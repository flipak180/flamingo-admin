<?php

namespace app\controllers;

use common\models\Stories\StoriesSearch;
use common\models\Stories\Story;
use common\models\Stories\StoryApiItem;
use yii\filters\auth\HttpBearerAuth;

class StoriesController extends BaseApiController
{
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

    /**
     * @return array
     */
    public function actionList()
    {
        $result = [];
        /** @var Story[] $stories */
        $stories = StoriesSearch::getApiList();
        foreach ($stories as $story) {
            $result[] = StoryApiItem::from($story)->attributes;
        }
        return $result;
    }

}
