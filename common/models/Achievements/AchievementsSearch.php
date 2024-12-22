<?php

namespace common\models\Achievements;

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
        foreach ($achievementCategories as $achievementCategory) {
            $achievements = [];
            foreach ($achievementCategory->achievements as $achievement) {
                $achievements[] = [
                    'id' => $achievement->id,
                    'title' => $achievement->title,
                    'description' => $achievement->description,
                ];
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
