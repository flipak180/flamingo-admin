<?php

namespace app\controllers;

use common\models\Event;

class EventsController extends BaseApiController
{

    public function actionList()
    {
        $result = [];
        /** @var Event[] $events */
        $events = Event::find()->orderBy('event_id DESC')->all();
        foreach ($events as $event) {
            $place = $event->place ? [
                [
                    'id' => $event->place->place_id,
                    'title' => $event->place->title,
                    'image' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                    'lastVisit' => null,
                    'atPlace' => false,
                ]
            ] : [];

            $result[] = [
                'id' => $event->event_id,
                'type' => $event->subtitle,
                'title' => $event->title,
                'image' => $event->image,
                'totalPlaces' => 0,
                'places' => $place,
            ];
        }

        return $result;
    }

    public function actionDetails($id)
    {
        /** @var Event $events */
        $event = Event::findOne($id);

        $place = $event->place ? [
            [
                'id' => $event->place->place_id,
                'title' => $event->place->title,
                'image' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'lastVisit' => null,
                'atPlace' => false,
            ]
        ] : [];

        return [
            'id' => $event->event_id,
            'type' => $event->subtitle,
            'title' => $event->title,
            'image' => $event->image,
            'description' => $event->description,
            'totalPlaces' => 0,
            'places' => $place,
        ];
    }

}
