<?php

namespace common\models\Places;

use common\models\Categories\Category;

class PlacesSearch extends Place
{

    /**
     * @param $category_id
     * @param $tag_id
     * @return Place[]
     */
    public static function getByCategory($category_id = null, $tag_id = null)
    {
        $tagIds = [];
        $category = Category::findOne($category_id);
        if ($category && !$tag_id) {
            foreach ($category->categoryTags as $categoryTag) {
                $tagIds[] = $categoryTag->tag_id;
            }
        } else {
            $tagIds = [$tag_id];
        }

        //$orderDir = ($category->type == Category::TYPE_CATALOG) ? 'DESC' : 'ASC';

        return Place::find()->joinWith(['placeTags', 'placeCategories', 'rates'])
            ->andWhere('places.in_trash IS NOT TRUE')
            ->andWhere([
                $tag_id ? 'and' : 'or',
                ['place_categories.category_id' => $category_id],
                ['in', 'place_tags.tag_id', $tagIds]
            ])
            ->orderBy('position ASC')
            //->orderBy(new Expression('position ASC, coalesce(places.sort_title, places.title) ASC'))
            ->limit(20)
            ->all();
    }

    /**
     * @param $term
     * @return Place[]
     */
    public static function getByTerm($term = '')
    {
        return Place::find()
            ->where(['ilike', 'title', $term])
            ->orWhere(['ilike', 'full_title', $term])
            ->orderBy('position ASC')
            //->orderBy(new Expression('position ASC, coalesce(places.sort_title, places.title) ASC'))
            ->all();
    }

    /**
     * @param Place $place
     * @return Place[]
     */
    public static function findDuplicates($place)
    {
        if ($place->isNewRecord) {
            return [];
        }

        return Place::find()
            ->where(['not in', 'place_id', $place->place_id])
            ->andWhere(['OR',
                ['ilike', 'title', $place->title],
                ['ilike', 'full_title', $place->title]
            ])
            ->all();
    }
}
