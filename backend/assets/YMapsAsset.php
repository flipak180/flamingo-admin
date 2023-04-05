<?php

namespace backend\assets;

class YMapsAsset extends \yii\web\AssetBundle
{
    const API_KEY = '048d2b9a-9e4a-481c-9799-c8f42c0ce65a';

    public $sourcePath = '@backend/widgets/YMaps/web';

    public $publishOptions = [
        'forceCopy' => true,
    ];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->js = [
            'https://api-maps.yandex.ru/2.1/?apikey=' . self::API_KEY . '&lang=ru_RU',
            'coords-input.js',
        ];
        $this->css = [
            'coords-input.css',
        ];
        parent::init();
    }
}
