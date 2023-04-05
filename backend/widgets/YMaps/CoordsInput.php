<?php

namespace backend\widgets\YMaps;

use backend\assets\YMapsAsset;
use yii\helpers\ArrayHelper;
use yii\widgets\InputWidget;

class CoordsInput extends InputWidget
{
    /**
     * @var array
     */
    public $ymapsClientOptions = [];

    /**
     * @var array
     */
    protected $defaultYmapsClientOptions = [
        'zoom' => 17,
        'controls' => ['fullscreenControl', 'zoomControl'],
    ];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        YMapsAsset::register($this->view);
        $this->options['class'] = 'form-control coords-input';
        $this->options['data']['map'] = ArrayHelper::merge(
            $this->defaultYmapsClientOptions,
            $this->ymapsClientOptions
        );
        return $this->render('coords-input', [
            'widget' => $this,
            'hasModel' => $this->hasModel(),
        ]);
    }

}
