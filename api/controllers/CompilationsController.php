<?php

namespace api\controllers;

use api\models\Compilations\CompilationApiItem;
use common\models\Compilations\Compilation;
use OpenApi\Attributes as OA;
use yii\db\Expression;

class CompilationsController extends BaseApiController
{
    public $modelClass = 'common\models\Compilations\Compilation';

    #[OA\Get(path: '/api/compilations/get-actual-compilations', tags: ['compilations'])]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionGetActualCompilations()
    {
        $result = [];

        /** @var Compilation[] $compilations */
        $compilations = Compilation::find()
            ->where(['is_actual' => true])
            ->orderBy(new Expression('random()'))
            ->all();

        foreach ($compilations as $compilation) {
            $result[] = CompilationApiItem::from($compilation);
        }

        return $result;
    }

    #[OA\Get(
        path: '/api/compilations/details',
        tags: ['compilations'],
        parameters: [
            new OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionDetails($id)
    {
        /** @var Compilation $compilation */
        $compilation = Compilation::find()->where(['compilation_id' => $id])->with('places')->one();
        if (!$compilation) {
            return null;
        }

        return CompilationApiItem::from($compilation);
    }
}
