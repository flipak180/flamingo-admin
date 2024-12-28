<?php

namespace api\models;

use yii\base\Model;

interface ApiItem
{
    public static function from(Model $model, array $extra = []): array;
}
