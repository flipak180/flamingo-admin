<?php

namespace api\controllers;

use common\models\Ticket;
use OpenApi\Attributes as OA;
use Yii;

class TicketsController extends BaseApiController
{
    #[OA\Post(
        path: '/api/tickets/create',
        tags: ['tickets'],
        parameters: [
            new OA\Parameter(name: 'user_id', description: 'User ID', in: 'query', required: true),
            new OA\Parameter(name: 'type', description: 'Type', in: 'query', required: true),
            new OA\Parameter(name: 'message', description: 'Message', in: 'query', required: true),
            new OA\Parameter(name: 'images_field', description: 'Images', in: 'query'),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionCreate()
    {
        $ticket = new Ticket();
        $ticket->load(Yii::$app->request->post());

        if (!$ticket->save()) {
            throw new \yii\web\HttpException(500, 'Unable to create ticket.');
        }
    }
}
