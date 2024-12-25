<?php

namespace common\base;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class DataTransferObject extends Model
{
    /**
     * @var array
     */
    protected $extra = [];

    /**
     * @var
     */
    public $id;

    /**
     * @param array $extra
     * @return DataTransferObject
     */
    public static function create(array $extra = []): DataTransferObject
    {
        $model = new static();
        $model->extra = $extra;
        return $model;
    }

    /**
     * @param $model
     * @return $this
     */
    public function from($model): DataTransferObject
    {
        $this->attributes = $model->attributes;
        return $this;
    }

    /**
     * @param $key
     * @param $default
     * @return mixed
     * @throws \Exception
     */
    protected function getFromExtra($key, $default = null)
    {
        \Yii::info($this->extra);
        return ArrayHelper::getValue($this->extra, $key, $default);
    }
}
