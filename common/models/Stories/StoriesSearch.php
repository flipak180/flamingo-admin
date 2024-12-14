<?php

namespace common\models\Stories;

use common\models\Places\Place;

class StoriesSearch extends Story
{

    /**
     * @param $from_timestamp
     * @return Place[]
     */
    public static function getApiList($from_timestamp = null)
    {
        $datetime = date('Y-m-d H:i:s', $from_timestamp);

        return Story::find()
            ->where(['>', 'created_at', $datetime])
            ->all();
    }
}
