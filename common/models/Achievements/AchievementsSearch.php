<?php

namespace common\models\Achievements;

use Yii;
use yii\helpers\ArrayHelper;

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
        $achievementsProgress = AchievementProgress::getMapByUserId(Yii::$app->user->id);
        foreach ($achievementCategories as $achievementCategory) {
            $achievements = [];
            foreach ($achievementCategory->achievements as $achievement) {
                $progress = ArrayHelper::getValue($achievementsProgress, $achievement->id, [
                    'points' => 0,
                    'unlocked_at' => null
                ]);
                $achievements[] = [
                    'id' => $achievement->id,
                    'title' => $achievement->title,
                    'description' => $achievement->description,
                    'points' => $achievement->points,
                    'progress' => $progress,
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
