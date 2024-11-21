<?php

namespace common\models\Places;

use common\behaviors\ImageBehavior;
use common\models\Category;
use common\models\ImageModel;
use common\models\PlaceCategory;
use common\models\PlaceRate;
use common\models\PlaceTag;
use common\models\Tag;
use common\models\Visit;
use nanson\postgis\behaviors\GeometryBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "places".
 *
 * @property int $place_id
 * @property string $title
 * @property string $full_title
 * @property string $sort_title
 * @property string|null $description
 * @property string $location
 * @property string $coords
 * @property string $address
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category $category
 * @property PlaceTag[] $placeTags
 * @property PlaceCategory[] $placeCategories
 * @property Tag[] $tags
 * @property Category[] $categories
 * @property Visit[] $visits
 * @property ImageModel[] $images
 * @property PlaceRate[] $rates
 */
class Place extends \yii\db\ActiveRecord
{
    public $location_field;
    public $coords_field;
    public $tags_field;
    public $images_field;
    public $categories_field;

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
            [['title', 'full_title', 'sort_title', 'address'], 'string', 'max' => 255],
            [['tags_field', 'location_field', 'coords_field', 'images_field', 'categories_field'], 'safe'],
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

        foreach ($this->placeCategories as $placeCategory) {
            $this->categories_field[] = $placeCategory->category_id;
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
        $this->handleCategories();
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
            'full_title' => 'Полное название',
            'sort_title' => 'Название для сортировки',
            'description' => 'Описание',
            'address' => 'Адрес',
            'location' => 'Местоположение',
            'location_field' => 'Местоположение',
            'coords' => 'Координаты',
            'coords_field' => 'Координаты',
            'images_field' => 'Изображения',
            'categories_field' => 'Категории',
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
     * @return \yii\db\ActiveQuery
     */
    public function getPlaceCategories()
    {
        return $this->hasMany(PlaceCategory::className(), ['place_id' => 'place_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['category_id' => 'category_id'])
            ->via('placeCategories');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRates()
    {
        return $this->hasMany(PlaceRate::className(), ['place_id' => 'place_id']);
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
     * @return void
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function handleCategories() {
        if (!is_array($this->categories_field)) {
            return;
        }

        /** @var Category[] $currentCategories */
        $currentCategories = $this->getCategories()->all();

        foreach ($currentCategories as $currentCategory) {
            if (!in_array($currentCategory->title, $this->categories_field)) {
                $this->unlink('categories', $currentCategory, true);
            }
        }

        foreach ($this->categories_field as $categoryId) {
            $category = Category::findOne($categoryId);
            $this->link('categories', $category);
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

    /**
     * @return string
     */
    public function getCategoriesLabels()
    {
        $labels = [];
        foreach ($this->categories as $category) {
            $labels[] = '<span class="badge bg-dark">'.$category->title.'</span>';
        }
        return implode(' ', $labels);
    }

    /**
     * @return array
     */
    public function getStats()
    {
        $total_rates = PlaceRate::find()
            ->where(['place_id' => $this->place_id])
            ->count();

        $total_likes = PlaceRate::find()
            ->where(['place_id' => $this->place_id, 'rate' => 1])
            ->count();

        return [
            'total_rates' => $total_rates,
            'likes_percent' => $total_likes ? round($total_likes / $total_rates * 100) : 0,
        ];
    }
}
