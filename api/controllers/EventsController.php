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
            $result[] = [
                'id' => $event->event_id,
                'type' => $event->subtitle,
                'title' => $event->title,
                'image' => $event->image,
                'totalPlaces' => 0,
                'places' => [],
            ];
        }

        return $result;
    }

    public function actionDetails($id)
    {
        /** @var Event $events */
        $event = Event::findOne($id);
        return [
            'id' => $event->event_id,
            'type' => $event->subtitle,
            'title' => $event->title,
            'image' => $event->image,
            'description' => $event->description,
            'totalPlaces' => 0,
            'places' => [],
        ];
    }

}
