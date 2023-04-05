<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "places".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $category_id
 * @property float $latitude
 * @property float $longitude
 * @property float|null $radius
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
    public $coords_field;
    public $tags_field;

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
            TimestampBehavior::className()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'latitude', 'longitude'], 'required'],
            [['coords_field'], 'required', 'on' => 'form'],
            [['description'], 'string'],
            [['category_id', 'in_trash'], 'integer'],
            [['latitude', 'longitude', 'radius'], 'number'],
            [['title'], 'string', 'max' => 255],
            [['coords_field', 'tags_field'], 'safe'],
        ];
    }

    /**
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->coords_field) {
            $coords = explode(',', $this->coords_field);
            if (count($coords) == 2) {
                $this->latitude = trim($coords[0]);
                $this->longitude = trim($coords[1]);
            }
        }

        return parent::beforeValidate();
    }

    /**
     * @return void
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->coords_field = implode(', ', [$this->latitude, $this->longitude]);

        foreach ($this->placeTags as $placeTag) {
            $this->tags_field[] = $placeTag->tag_id;
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
            'id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание',
            'category_id' => 'Категория',
            'latitude' => 'Широта',
            'longitude' => 'Долгота',
            'coords_field' => 'Координаты',
            'radius' => 'Радиус',
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
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::className(), ['place_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceTags()
    {
        return $this->hasMany(PlaceTag::className(), ['place_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
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
}
