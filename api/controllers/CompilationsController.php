<?php

namespace app\controllers;

use common\models\Compilations\Compilation;
use common\models\Compilations\CompilationApiItem;
use yii\db\Expression;

class CompilationsController extends BaseApiController
{
    public $modelClass = 'common\models\Compilations\Compilation';

    /**
     * @return array
     */
    public function actionGetActualCompilation()
    {
        /** @var Compilation $compilation */
        $compilation = Compilation::find()
            ->where(['is_actual' => true])
            ->orderBy(new Expression('random()'))
            ->one();

        if (!$compilation) {
            return null;
        }

        return CompilationApiItem::from($compilation)->attributes;
    }
}
