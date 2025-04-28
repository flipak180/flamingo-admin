<?php

namespace api\models\PetersEyes;

use api\models\ApiItem;
use common\models\PetersEyes\PetersEye;
use common\models\PetersEyes\PetersEyeService;

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
            'status' => PetersEyeService::getUserStatus(),
            'image' => $model->image->path,
        ];
    }
}
