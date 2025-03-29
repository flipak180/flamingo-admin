<?php

namespace api\models\UserProgress;

use api\models\ApiItem;
use common\models\Stories\Story;
use himiklab\thumbnail\EasyThumbnailImage;
use Yii;
use yii\helpers\ArrayHelper;

class UserProgressApiItem implements ApiItem
{

    /**
     * @param array $data
     * @param array $extra
     * @return array
     * @throws \himiklab\thumbnail\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public static function from($data, array $extra = []): array
    {
        $smallImage = $data['image']
            ? EasyThumbnailImage::thumbnailFileUrl(Yii::getAlias('@frontend_web').$data['image'], 100, 82, EasyThumbnailImage::THUMBNAIL_OUTBOUND, 100)
            : null;

        return ArrayHelper::merge($data, [
            'smallImage' => $smallImage,
        ]);
    }

}
