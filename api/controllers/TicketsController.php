<?php

namespace api\controllers;

use common\models\Ticket;
use OpenApi\Attributes as OA;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class TicketsController extends BaseApiController
{
    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        return $behaviors;
    }

    #[OA\Post(
        path: '/api/tickets/create',
        tags: ['tickets'],
        parameters: [
            new OA\Parameter(name: 'type', description: 'Type', in: 'query', required: true),
            new OA\Parameter(name: 'message', description: 'Message', in: 'query', required: true),
            new OA\Parameter(name: 'images_field', description: 'Images', in: 'query'),
        ]
    )]
    #[OA\Response(response: '200', description: 'OK')]
    public function actionCreate()
    {
        $type = Yii::$app->request->post('type');
        $message = Yii::$app->request->post('message');
        $images = Yii::$app->request->post('images');

        $ticket = new Ticket();
        $ticket->type = $type;
        $ticket->message = $message;
        $ticket->user_id = Yii::$app->user->id;
        $ticket->images_field = $images;

        if (!$ticket->save()) {
            throw new \yii\web\HttpException(500, 'Unable to create ticket.');
        }

        return $ticket;
    }
}
