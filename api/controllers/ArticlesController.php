<?php

namespace api\controllers;

use common\models\Article;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class ArticlesController extends BaseApiController
{

    public function actionList($limit = 10, $offset = 0)
    {
        $result = [];
        /** @var Article[] $articles */
        $articles = Article::find()
            ->andWhere(['not in', 'id', [8, 9]])
            ->orderBy('id DESC')->limit($limit)->offset($offset)->all();
        foreach ($articles as $article) {
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
                $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 716, 600, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
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
            $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 716, 600, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
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
