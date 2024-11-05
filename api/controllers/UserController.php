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

        $user = User::findOne(['phone' => User::encryptPhone($phone)]);
        if (!$user) {
            return $this->error(404, 'Номер не зарегистрирован.');
        }

        return $this->response($user);
    }
}
