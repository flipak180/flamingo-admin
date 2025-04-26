<?php

namespace api\models\PetersEyes;

use api\models\ApiItem;
use common\models\PetersEyes\PetersEye;
use Yii;

class PetersEyeApiItem implements ApiItem
{

    /**
     * @param PetersEye $model
     * @param array $extra
     * @return array
     */
    public static function from($model, array $extra = []): array
    {
        return [
            'id' => $model->id,
            'prize' => $model->prize,
            'image' => Yii::$app->user->id ? $model->image->path : null,
        ];
    }
}
