<?php

namespace app\controllers;

use common\components\Helper;
use common\models\User;
use Yii;

class UserController extends BaseApiController
{
    public $modelClass = 'common\models\User';

    /**
     * @return array
     */
    public function actionAuth()
    {
        $phone = Yii::$app->request->post('phone');
        $phone = Helper::clearPhone($phone);
        if (!$phone) {
            return $this->error(400, 'Не указан номер телефона.');
        }

        $phone = User::encryptPhone($phone);
        $user = User::findOne(['phone' => $phone]);
        if (!$user) {
            $user = new User();
            $user->name = User::DEFAULT_NAME;
            $user->phone = $phone;
            if (!$user->save()) {
                return $this->error(500, 'Произошла ошибка.');
            }
        }

        return $this->response([
            'token' => $user->phone,
            'name' => $user->name,
        ]);
    }
}
