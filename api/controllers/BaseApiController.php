<?php

namespace app\controllers;

use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\Response;

abstract class BaseApiController extends Controller
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

    /**
     * @return \string[][]
     */
    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction'
            ]
        ];
    }

    /**
     * @param $code
     * @param $desc
     * @return array
     */
    protected function error($code, $desc = '')
    {
        return $this->response(['code' => $code, 'desc' => $desc], 1);
    }

    /**
     * @param $data
     * @param $error
     * @param $onlyData
     * @return array
     */
    protected function response($data, $error = 0, $onlyData = false)
    {
        return (!$onlyData) ? ['error' => $error, 'data' => $data] : $data;
    }
}
