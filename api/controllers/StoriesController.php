<?php

namespace api\controllers;

use api\models\Stories\StoryApiItem;
use common\models\Stories\StoriesSearch;
use common\models\Stories\Story;
use Yii;

class StoriesController extends BaseApiController
{

    /**
     * @return array
     */
    public function actionList()
    {
        $from_timestamp = Yii::$app->request->get('from_timestamp');

        $result = [];
        /** @var Story[] $stories */
        $stories = StoriesSearch::getApiList($from_timestamp);
        foreach ($stories as $story) {
            $result[] = StoryApiItem::from($story);
        }
        return $this->response($result);
    }

}
