<?php

namespace common\models\Stories;

use common\components\Helper;
use common\models\Places\Place;

class StoriesSearch extends Story
{

    /**
     * @param $from_timestamp
     * @return Place[]
     */
    public static function getApiList($from_timestamp = null)
    {
        $datetime = Helper::formatDate($from_timestamp, true);

        return Story::find()
            ->where(['>', 'created_at', $datetime])
            ->limit(5)
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }
}
