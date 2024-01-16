<?php

namespace app\controllers;

use common\models\Quest;

class QuestsController extends BaseApiController
{

    public function actionList()
    {
        $result = [];
        /** @var Quest[] $quests */
        $quests = Quest::find()->orderBy('id DESC')->all();
        foreach ($quests as $quest) {
            if ($quest->id == 8 || $quest->id == 9) {
                continue;
            }

            $places = [];
            foreach ($quest->places as $place) {
                $places[] = [
                    'id' => $place->place_id,
                    'title' => $place->title,
                    'coords' => $place->coords,
                    'image' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                    'lastVisit' => null,
                    'atPlace' => false,
                ];
            }

            $images = [];
            foreach ($quest->images as $image) {
                $images[] = $image->path;
            }

            $result[] = [
                'id' => $quest->id,
                'type' => $quest->subtitle,
                'title' => $quest->title,
                'image' => count($images) ? $images[0] : '',
                'images' => $images,
                'totalPlaces' => 0,
                'places' => $places,
            ];
        }

        return $result;
    }

    /**
     * @param $id
     * @return array
     */
    public function actionDetails($id)
    {
        /** @var Quest $quest */
        $quest = Quest::findOne($id);

        $places = [];
        foreach ($quest->places as $place) {
            $places[] = [
                'id' => $place->place_id,
                'title' => $place->title,
                'coords' => $place->coords,
                'image' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'lastVisit' => null,
                'atPlace' => false,
            ];
        }

        $images = [];
        foreach ($quest->images as $image) {
            $images[] = $image->path;
        }

        return [
            'id' => $quest->id,
            'type' => $quest->subtitle,
            'title' => $quest->title,
            'image' => count($images) ? $images[0] : '',
            'images' => $images,
            'description' => $quest->description,
            'totalPlaces' => 0,
            'places' => $places,
        ];
    }

}
