<?php

namespace common\models;

use common\behaviors\ImageBehavior;
use common\behaviors\PlaceBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "articles".
 *
 * @property int $id
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Place[] $places
 */
class Article extends \yii\db\ActiveRecord
{
    public $images_field;
    public $places_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articles';
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
            [
                'class' => ImageBehavior::class,
                'attribute' => 'images_field',
            ],
            [
                'class' => PlaceBehavior::class,
                'attribute' => 'places_field',
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
            [['title', 'subtitle'], 'string', 'max' => 255],
            [['places_field', 'images_field'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'subtitle' => 'Подзаголовок',
            'description' => 'Описание',
            'images_field' => 'Изображения',
            'places_field' => 'Места',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticlePlaces()
    {
        return $this->hasMany(ArticlePlace::className(), ['article_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::className(), ['place_id' => 'place_id'])
            ->via('articlePlaces');
    }
}
