<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "compilations".
 *
 * @property int $compilation_id
 * @property string $title
 * @property string|null $image
 * @property string|null $description
 * @property bool|null $in_trash
 * @property string $created_at
 * @property string $updated_at
 *
 * @property CompilationPlace[] $compilationPlaces
 * @property Place[] $places
 */
class Compilation extends \yii\db\ActiveRecord
{
    public $image_field;
    public $places_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'compilations';
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
            [['title'], 'required'],
            [['description'], 'string'],
            [['in_trash'], 'boolean'],
            [['title', 'image'], 'string', 'max' => 255],
            [['image_field'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 1024*1024],
            ['places_field', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'compilation_id' => 'ID',
            'title' => 'Название',
            'image' => 'Изображение',
            'image_field' => 'Изображение',
            'description' => 'Описание',
            'places_field' => 'Места',
            'in_trash' => 'В корзине',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompilationPlaces()
    {
        return $this->hasMany(CompilationPlace::className(), ['compilation_id' => 'compilation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::className(), ['place_id' => 'place_id'])
            ->via('compilationPlaces');
    }
}
