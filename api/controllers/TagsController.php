<?php

namespace api\controllers;

use common\models\Tags\TagSearch;
use OpenApi\Attributes as OA;

class TagsController extends BaseApiController
{
    public $modelClass = 'common\models\Tags\Tag';

    #[OA\Get(path: '/api/tags/list', tags: ['tags'])]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionList()
    {
        $data = TagSearch::getList();
        return $this->response($data);
    }

}
