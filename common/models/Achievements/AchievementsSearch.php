<?php

namespace common\models\Achievements;

use Yii;

/**
 *
 */
class AchievementsSearch extends Achievement
{
    public static function getListByCategories()
    {
        $result = [];
        /** @var AchievementCategory[] $achievementCategories */
        $achievementCategories = AchievementCategory::find()->with(['achievements'])->all();
        $achievementsProgressMap = AchievementProgress::getMapByUserId(Yii::$app->user->id);
        foreach ($achievementCategories as $achievementCategory) {
            $achievements = [];
            foreach ($achievementCategory->achievements as $achievement) {
                $achievements[] = AchievementApiItem::create(
                    ['achievementsProgressMap' => $achievementsProgressMap]
                )->from($achievement)->attributes;
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
