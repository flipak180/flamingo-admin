<?php

namespace common\components;

use yii\base\InvalidArgumentException;

class Coordinates
{
    private $coords;
    public $latitude;
    public $longitude;

    public function __construct($coords)
    {
        $this->coords = match (gettype($coords)) {
            'string' => explode(',', str_replace(' ', '', $coords)),
            'array' => $coords,
            default => throw new InvalidArgumentException('coords must be string or array'),
        };

        $this->latitude = $this->coords[0];
        $this->longitude = $this->coords[1];
    }

}
