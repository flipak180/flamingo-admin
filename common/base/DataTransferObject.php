<?php

namespace common\base;

abstract class DataTransferObject extends \yii\base\Model
{
    public static $_extra = [];
    public $show_checkbox = true;
    public $id;

    abstract public static function from($model);
}
