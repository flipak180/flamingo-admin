<?php

namespace api\models\PetersEyes;

use api\models\ApiItem;
use common\models\PetersEyes\PetersEye;
use common\models\PetersEyes\PetersEyeService;
use common\models\PetersEyes\PetersEyeUser;

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
            'status' => (new PetersEyeService($model))->getUserStatus(),
            'image' => $model->image->path,
            'total_users' => PetersEyeUser::find()->where(['peters_eye_id' => $model->id])->count(),
        ];
    }
}
