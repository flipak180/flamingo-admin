<?php

namespace common\models\Achievements;

use api\models\Achievements\AchievementApiItem;
use Yii;

/**
 *
 */
class AchievementsSearch extends Achievement
{
    /**
     * @return array
     */
    public static function getList()
    {
        $result = [];
        $achievementsProgressMap = AchievementProgress::getMapByUserId(Yii::$app->user->id);
        /** @var Achievement $models */
        $models = Achievement::find()->where(['status' => Achievement::STATUS_ACTIVE])->all();
        foreach ($models as $model) {
            $result[] = AchievementApiItem::from($model, [
                'achievementsProgressMap' => $achievementsProgressMap
            ]);
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getListByCategories()
    {
        $result = [];
        /** @var AchievementCategory[] $achievementCategories */
        $achievementCategories = AchievementCategory::find()->with(['achievements'])->all();
        $achievementsProgressMap = AchievementProgress::getMapByUserId(Yii::$app->user->id);
        foreach ($achievementCategories as $achievementCategory) {
            $achievements = [];
            foreach ($achievementCategory->achievements as $achievement) {
                $achievements[] = AchievementApiItem::from($achievement, [
                    'achievementsProgressMap' => $achievementsProgressMap
                ]);
            }
            $result[] = [
                'id' => $achievementCategory->id,
                'title' => $achievementCategory->title,
                'achievements' => $achievements
            ];
        }
        return $result;
    }
}
