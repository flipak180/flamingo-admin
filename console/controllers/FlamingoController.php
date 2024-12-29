<?php

namespace console\controllers;

use OpenApi\Generator;
use Yii;
use yii\console\Controller;

class FlamingoController extends Controller
{

    public function actionGenerateDocs()
    {
        $openapi = Generator::scan([Yii::getAlias('@api')]);

        header('Content-Type: application/x-yaml');
        echo $openapi->toYaml();


//        $openApi = Generator::scan([Yii::getAlias('@api')]);
//        $file = Yii::getAlias('@api') . '/web/api-doc/swagger.yaml';
//        $handle = fopen($file, 'wb');
//        fwrite($handle, $openApi->toYaml());
//        fclose($handle);
    }

}
