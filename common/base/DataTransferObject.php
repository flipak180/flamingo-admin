<?php

namespace common\base;

abstract class DataTransferObject extends \yii\base\Model
{
    public static $_extra = [];

    public $id;

    abstract public static function from($model);
}
