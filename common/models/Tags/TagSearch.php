<?php

namespace common\models\Tags;

/**
 *
 */
class TagSearch extends Tag
{
    /**
     * @return array
     */
    public static function getList()
    {
        $result = [];
        /** @var Tag[] $models */
        $models = Tag::find()->all();
        foreach ($models as $model) {
            $result[] = [
                'id' => $model->tag_id,
                'title' => $model->title,
                'full_title' => $model->full_title,
            ];
        }
        return $result;
    }
}
