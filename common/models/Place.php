<?php

namespace common\models;

use common\behaviors\ImageBehavior;
use nanson\postgis\behaviors\GeometryBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "places".
 *
 * @property int $place_id
 * @property string $title
 * @property string|null $description
 * @property string $location
 * @property string $coords
 * @property int|null $category_id
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category $category
 * @property PlaceTag[] $placeTags
 * @property Tag[] $tags
 * @property Visit[] $visits
 */
class Place extends \yii\db\ActiveRecord
{
    public $location_field;
    public $coords_field;
    public $tags_field;
    public $images_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'places';
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
                'class' => GeometryBehavior::class,
                'type' => GeometryBehavior::GEOMETRY_POLYGON,
                'attribute' => 'location',
            ],
            [
                'class' => GeometryBehavior::class,
                'type' => GeometryBehavior::GEOMETRY_POINT,
                'attribute' => 'coords',
            ],
            [
                'class' => ImageBehavior::class,
                'attribute' => 'images_field',
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
            [['title'], 'unique'],
            //[['location_field'], 'required', 'on' => 'form'],
            [['description'], 'string'],
            [['in_trash'], 'boolean'],
            [['category_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['tags_field', 'location_field', 'coords_field', 'images_field'], 'safe'],
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->location_field) {
            $this->location = json_decode($this->location_field, true);
        }
        if ($this->coords_field) {
            $this->coords = array_map('trim', explode(',', $this->coords_field));
        }
        return parent::beforeValidate();
    }

    /**
     * @return void
     */
    public function afterFind()
    {
        parent::afterFind();
        foreach ($this->placeTags as $placeTag) {
            $this->tags_field[] = $placeTag->tag_id;
        }

        if ($this->location) {
            $this->location_field = json_encode($this->location);
        }
        if ($this->coords) {
            $this->coords_field = implode(', ', $this->coords);
        }
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->handleTags();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'place_id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание',
            'location' => 'Местоположение',
            'location_field' => 'Местоположение',
            'coords' => 'Координаты',
            'coords_field' => 'Координаты',
            'images_field' => 'Изображения',
            'category_id' => 'Категория',
            'tags_field' => 'Теги',
            'in_trash' => 'В корзине',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::className(), ['place_id' => 'place_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceTags()
    {
        return $this->hasMany(PlaceTag::className(), ['place_id' => 'place_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['tag_id' => 'tag_id'])
            ->via('placeTags');
    }

    /**
     * @return void
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function handleTags() {
        if (!is_array($this->tags_field)) {
            return;
        }

        /** @var Tag[] $currentTags */
        $currentTags = $this->getTags()->all();

        foreach ($currentTags as $currentTag) {
            if (!in_array($currentTag->title, $this->tags_field)) {
                $this->unlink('tags', $currentTag, true);
            }
        }

        foreach ($this->tags_field as $tagId) {
            $tag = Tag::findOne($tagId);
            $this->link('tags', $tag);
        }
    }

    /**
     * @return string
     */
    public function getTagsLabels()
    {
        $labels = [];
        foreach ($this->tags as $tag) {
            $labels[] = '<span class="badge bg-dark">'.$tag->title.'</span>';
        }
        return implode(' ', $labels);
    }
}
