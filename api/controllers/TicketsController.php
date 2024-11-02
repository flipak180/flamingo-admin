<?php

namespace app\controllers;

use common\models\Ticket;
use Yii;

class TicketsController extends BaseApiController
{
    public function actionCreate()
    {
        $ticket = new Ticket();
        $ticket->load(Yii::$app->request->post());

        if (!$ticket->save()) {
            throw new \yii\web\HttpException(500, 'Unable to create ticket.');
        }
    }
}
