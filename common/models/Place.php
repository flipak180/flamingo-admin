<?php

namespace common\models;

use nanson\postgis\behaviors\GeometryBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "places".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $location
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property PlaceTag[] $placeTags
 * @property Tag[] $tags
 * @property Visit[] $visits
 */
class Place extends \yii\db\ActiveRecord
{
    public $location_field;
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
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => GeometryBehavior::className(),
                'type' => GeometryBehavior::GEOMETRY_POLYGON,
                'attribute' => 'location',
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
            [['location_field'], 'required', 'on' => 'form'],
            [['description'], 'string'],
            [['in_trash'], 'boolean'],
            [['title'], 'string', 'max' => 255],
            [['tags_field', 'location_field'], 'safe'],
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

        $this->location_field = json_encode($this->location);
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
            'location' => 'Местоположение',
            'location_field' => 'Местоположение',
            'tags_field' => 'Теги',
            'in_trash' => 'В корзине',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
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
