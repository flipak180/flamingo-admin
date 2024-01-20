<?php

namespace common\models;

use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class QuestReadModel
{
    /** @var Quest $quest */
    private $quest;

    /**
     * @param Quest $quest
     */
    public function __construct($quest)
    {
        $this->quest = $quest;
    }

    /**
     * @param $quest
     * @return self
     */
    public static function from($quest)
    {
        return new self($quest);
    }

    /**
     * @return array
     * @throws \himiklab\thumbnail\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function getShortInfo()
    {
        return [
            'id' => $this->quest->id,
            'type' => $this->quest->subtitle,
            'title' => $this->quest->title,
            'image' => $this->quest->firstImage
                ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$this->quest->firstImage->path, 716, 600, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
                : '',
            'totalPlaces' => count($this->quest->questPlaces),
        ];
    }

    /**
     * @return array
     * @throws \himiklab\thumbnail\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function getDetails()
    {
        $places = [];
        foreach ($this->quest->questPlaces as $place) {
            $places[] = [
                'id' => $place->id,
                'title' => $place->title,
                'coords' => $place->coords,
                'image' => sprintf('#%06X', mt_rand(0, 0xFFFFFF)),
                'lastVisit' => null,
                'atPlace' => false,
            ];
        }

        $images = [];
        foreach ($this->quest->images as $image) {
            $images[] = EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$image->path, 716, 600, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100);
        }

        return [
            'id' => $this->quest->id,
            'type' => $this->quest->subtitle,
            'title' => $this->quest->title,
            'image' => count($images) ? $images[0] : '',
            'images' => $images,
            'description' => $this->quest->description,
            'totalPlaces' => count($places),
            'places' => $places,
        ];
    }

}
