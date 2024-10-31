<?php

namespace app\controllers;

use common\models\Ticket;
use Yii;

class TicketsController extends BaseApiController
{
    public function actionCreate()
    {
        $type = Yii::$app->request->post('type');
        $message = Yii::$app->request->post('message');

        $ticket = new Ticket();
        $ticket->type = $type;
        $ticket->message = $message;

        if (!$ticket->save()) {
            throw new \yii\web\HttpException(500, 'Unable to create ticket.');
        }
    }
}
