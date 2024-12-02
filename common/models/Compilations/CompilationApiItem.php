<?php

namespace common\models\Compilations;

use common\base\DataTransferObject;

class CompilationApiItem extends DataTransferObject
{
    public $id;
    public $title;
    public $image;

    /**
     * @param Compilation $model
     * @return CompilationApiItem
     */
    public static function from($model): CompilationApiItem
    {
        $dto = new self();
        $dto->id = $model->compilation_id;
        $dto->title = $model->title;
        return $dto;
    }

}
