<?php

namespace app\controllers;

use common\models\Article;

class ArticlesController extends BaseApiController
{

    public function actionList()
    {
        $result = [];
        /** @var Article[] $articles */
        $articles = Article::find()->orderBy('id DESC')->all();
        foreach ($articles as $article) {
            if ($article->id == 8 || $article->id == 9) {
                continue;
            }

            $places = [];
            foreach ($article->places as $place) {
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
            foreach ($article->images as $image) {
                $images[] = $image->path;
            }

            $result[] = [
                'id' => $article->id,
                'type' => $article->subtitle,
                'title' => $article->title,
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
        /** @var Article $article */
        $article = Article::findOne($id);

        $places = [];
        foreach ($article->places as $place) {
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
        foreach ($article->images as $image) {
            $images[] = $image->path;
        }

        return [
            'id' => $article->id,
            'type' => $article->subtitle,
            'title' => $article->title,
            'image' => count($images) ? $images[0] : '',
            'images' => $images,
            'description' => $article->description,
            'totalPlaces' => 0,
            'places' => $places,
        ];
    }

}
