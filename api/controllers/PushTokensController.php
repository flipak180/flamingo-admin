<?php

namespace api\controllers;

use common\models\PushTokens\PushTokenActions;
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

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionRegister()
    {
        $token = Yii::$app->request->post('token');
        $res = PushTokenActions::register($token);
        return $this->response($res);
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionDetach()
    {
        $token = Yii::$app->request->post('token');
        $res = PushTokenActions::detach($token);
        return $this->response($res);
    }
}
