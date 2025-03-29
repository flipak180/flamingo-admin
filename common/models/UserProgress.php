<?php

namespace common\models;

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
        return Yii::$app->db->createCommand("
            SELECT categories.category_id, categories.title, categories.image,
                   count(places) as total_places, count(visits)
            FROM categories
            LEFT JOIN place_categories ON place_categories.category_id = categories.category_id
            LEFT JOIN places ON places.place_id = place_categories.place_id
            LEFT JOIN visits ON visits.place_id = places.place_id AND visits.user_id = :user_id
            GROUP BY categories.category_id, categories.title
            ORDER BY categories.title
        ")
            ->bindValue(':user_id', Yii::$app->user->id)
            ->queryAll();
    }
}
