<?php

namespace api\models\PetersEyes;

use api\models\ApiItem;
use common\models\PetersEyes\PetersEye;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;

class PetersEyeApiItem implements ApiItem
{

    /**
     * @param PetersEye $model
     * @param array $extra
     * @return array
     * @throws \himiklab\thumbnail\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public static function from($model, array $extra = []): array
    {
        $image = $model->image
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$model->image->path, 720, 400, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

        return [
            'id' => $model->id,
            'prize' => $model->prize,
            'image' => Yii::$app->user->id ? $image : null,
        ];
    }
}
