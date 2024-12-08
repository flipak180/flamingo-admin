<?php

namespace app\controllers;

use Yii;

class SystemController extends BaseApiController
{

    /**
     * @return true
     */
    public function actionSendSms()
    {
        $phone = Yii::$app->request->post('phone');
        $message = Yii::$app->request->post('message');

        return Yii::$app->sms->send($phone, $message);
    }

}
