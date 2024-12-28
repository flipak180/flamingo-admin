<?php

namespace api\models\Achievements;

use common\models\Achievements\Achievement;
use yii\helpers\ArrayHelper;

/**
 *
 */
class AchievementApiItem
{

    /**
     * @param Achievement $model
     * @param $extra
     * @return array
     */
    public static function from(Achievement $model, $extra = []): array
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
