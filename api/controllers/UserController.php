<?php

namespace app\controllers;

use common\models\User;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class UserController extends BaseApiController
{
    public $modelClass = 'common\models\User';

    /**
     * @return User|null
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogin()
    {
        $params = Yii::$app->request->getBodyParams();
        if (!isset($params['phone'])) {
            throw new BadRequestHttpException();
        }

        $user = User::findOne(['phone' => $params['phone']]);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        return $user;
    }
}
