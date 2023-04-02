<?php

namespace app\controllers;

use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\ActiveController;
use yii\web\Response;

class BaseApiController extends ActiveController
{

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'corsFilter' => [
                'class' => Cors::class,
            ],
        ];
    }

}
