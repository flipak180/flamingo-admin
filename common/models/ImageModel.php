<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "images".
 *
 * @property int $image_id
 * @property string $path
 * @property string $model
 * @property int $model_id
 * @property string|null $title
 * @property string $created_at
 * @property string $updated_at
 */
class ImageModel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'images';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['path', 'model', 'model_id'], 'required'],
            [['model_id'], 'default', 'value' => null],
            [['model_id'], 'integer'],
            [['path', 'model', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'image_id' => 'ID',
            'path' => 'Путь',
            'model' => 'Модель',
            'model_id' => 'ID модели',
            'title' => 'Название',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }
}
