<?php

namespace console\controllers;

use OpenApi\Generator;
use Yii;
use yii\console\Controller;

class FlamingoController extends Controller
{

    public function actionGenerateDocs()
    {
        $openApi = Generator::scan([Yii::getAlias('@api')]);
        $file = Yii::getAlias('@frontend_web') . '/openapi.yaml';
        $handle = fopen($file, 'wb');
        fwrite($handle, $openApi->toYaml());
        fclose($handle);
    }

}
