<?php

namespace common\models\Stories;

use common\models\Places\Place;

class StoriesSearch extends Story
{

    /**
     * @param $category_id
     * @param $tag_id
     * @return Place[]
     */
    public static function getApiList($category_id = null, $tag_id = null)
    {
        return Story::find()->all();
    }
}
