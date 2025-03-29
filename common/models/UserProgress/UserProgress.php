<?php

namespace common\models\UserProgress;

use api\models\UserProgress\UserProgressApiItem;
use Yii;

/**
 *
 */
class UserProgress
{

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public static function byCategories(): array
    {
        $result = [];

        $rows = Yii::$app->db->createCommand("
            SELECT categories.category_id, categories.title, categories.image,
                   count(places) as total_places, count(visits) as total_visited,
                   CASE count(places) WHEN 0 THEN 0 ELSE count(visits) * 100 / count(places) END as percentage
            FROM categories
            LEFT JOIN place_categories ON place_categories.category_id = categories.category_id
            LEFT JOIN places ON places.place_id = place_categories.place_id
            LEFT JOIN visits ON visits.place_id = places.place_id AND visits.user_id = :user_id
            GROUP BY categories.category_id, categories.title
            ORDER BY percentage DESC, categories.title
        ")
            ->bindValue(':user_id', Yii::$app->user->id)
            ->queryAll();

        foreach ($rows as $row) {
            $result[] = UserProgressApiItem::from($row);
        }

        return $result;
    }
}
