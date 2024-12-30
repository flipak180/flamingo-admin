<?php

namespace api\controllers;

use api\models\Stories\StoryApiItem;
use common\models\Stories\StoriesSearch;
use common\models\Stories\Story;
use OpenApi\Attributes as OA;
use Yii;

class StoriesController extends BaseApiController
{

    #[OA\Get(
        path: '/api/stories/list',
        tags: ['stories'],
        parameters: [
            new OA\Parameter(name: 'from_timestamp', description: 'From Timestamp', in: 'path'),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
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
