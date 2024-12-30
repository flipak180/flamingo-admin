<?php

namespace api\controllers;

use common\models\PushTokens\PushTokenActions;
use OpenApi\Attributes as OA;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class PushTokensController extends BaseApiController
{
    public $modelClass = 'common\models\PushTokens\PushToken';

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['register', 'detach'],
            'optional' => ['register', 'detach'],
        ];
        return $behaviors;
    }

    #[OA\Post(
        path: '/api/push-tokens/register',
        tags: ['push-tokens'],
        parameters: [
            new OA\Parameter(name: 'token', description: 'Token', in: 'query', required: true),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionRegister()
    {
        $token = Yii::$app->request->post('token');

        $res = PushTokenActions::register($token);
        return $this->response($res);
    }

    #[OA\Post(
        path: '/api/push-tokens/detach',
        tags: ['push-tokens'],
        parameters: [
            new OA\Parameter(name: 'token', description: 'Token', in: 'query', required: true),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionDetach()
    {
        $token = Yii::$app->request->post('token');

        $res = PushTokenActions::detach($token);
        return $this->response($res);
    }
}
