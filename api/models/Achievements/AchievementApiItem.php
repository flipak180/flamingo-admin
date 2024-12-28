<?php

namespace api\models\Achievements;

use api\models\ApiItem;
use common\models\Achievements\Achievement;
use yii\helpers\ArrayHelper;

/**
 *
 */
class AchievementApiItem implements ApiItem
{

    /**
     * @param Achievement $model
     * @param $extra
     * @return array
     */
    public static function from($model, $extra = []): array
    {
        $achievementsProgressMap = ArrayHelper::getValue($extra, 'achievementsProgressMap', []);
        $progress = ArrayHelper::getValue($achievementsProgressMap, $model->id, [
            'points' => 0,
            'unlocked_at' => null
        ]);

        return [
            'id' => $model->id,
            'title' => $model->title,
            'description' => $model->description,
            'points' => $model->points,
            'progress' => $progress,
            'category_id' => $model->category_id,
        ];
    }
}
