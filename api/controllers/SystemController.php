<?php

namespace api\controllers;

use common\components\Telegram;
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

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function actionSendCode()
    {
        $phone = Yii::$app->request->post('phone');
        $code = Yii::$app->request->post('code');

        return Telegram::sendConfirmationCode($phone, $code);
    }

}
