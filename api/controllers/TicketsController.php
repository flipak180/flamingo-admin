<?php

namespace api\controllers;

use common\components\Telegram;
use common\models\Ticket;
use OpenApi\Attributes as OA;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\HttpException;

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

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @throws HttpException
     */
    #[OA\Post(
        path: '/api/tickets/create',
        tags: ['tickets'],
        parameters: [
            new OA\Parameter(name: 'Ticket[type]', description: 'Type', in: 'query', required: true),
            new OA\Parameter(name: 'Ticket[message]', description: 'Message', in: 'query', required: true),
            new OA\Parameter(name: 'Ticket[images_field]', description: 'Images', in: 'query'),
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

        Telegram::sendNotification(
//            '<a href="'.Url::toRoute(['tickets/view', 'id' => $ticket->id], 'https').'">Новый тикет # '.$ticket->id.'</a>'
            '<a href="https://flamingo.spb.ru/admin/tickets/view?id='.$ticket->id.'">Новый тикет # '.$ticket->id.'</a>'
        );

        return $ticket;
    }
}
