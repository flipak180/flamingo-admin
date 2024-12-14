<?php

namespace app\controllers;

use common\models\Stories\StoriesSearch;
use common\models\Stories\Story;
use common\models\Stories\StoryApiItem;
use Yii;

class StoriesController extends BaseApiController
{
    /**
     * @return array
     */
//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => HttpBearerAuth::class,
//            'optional' => ['list'],
//        ];
//        return $behaviors;
//    }

    /**
     * @return array
     */
    public function actionList()
    {
        $from_timestamp = Yii::$app->request->post('from_timestamp');

        $result = [];
        /** @var Story[] $stories */
        $stories = StoriesSearch::getApiList($from_timestamp);
        foreach ($stories as $story) {
            $result[] = StoryApiItem::from($story)->attributes;
        }
        return $this->response($result);
    }

}
