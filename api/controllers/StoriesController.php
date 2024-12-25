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
    public function actionList()
    {
        $from_timestamp = Yii::$app->request->get('from_timestamp');

        $result = [];
        /** @var Story[] $stories */
        $stories = StoriesSearch::getApiList($from_timestamp);
        foreach ($stories as $story) {
            $result[] = StoryApiItem::create()->from($story)->attributes;
        }
        return $this->response($result);
    }

}
