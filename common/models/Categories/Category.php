<?php

namespace common\models\Categories;

use common\models\PlaceCategory;
use common\models\Places\Place;
use common\models\Tag;
use himiklab\sortablegrid\SortableGridBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * This is the model class for table "categories".
 *
 * @property int $category_id
 * @property string $title
 * @property string|null $image
 * @property string|null $icon
 * @property int $type
 * @property int|null $parent_id
 * @property int $position
 * @property int $show_on_homepage
 * @property int $is_popular
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category $parent
 * @property Category $children
 * @property CategoryTag[] $categoryTags
 * @property PlaceCategory[] $categoryPlaces
 * @property Tag[] $tags
 * @property Place[] $places
 */
class Category extends \yii\db\ActiveRecord
{
    public $tags_field;
    public $image_field;

    const TYPE_CATALOG = 1;
    const TYPE_ROUTE = 2;
    const TYPE_QUEST = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'categories';
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
            'sort' => [
                'class' => SortableGridBehavior::className(),
                'sortableAttribute' => 'position'
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'type'], 'required'],
            [['parent_id', 'type', 'position'], 'integer'],
            [['in_trash', 'show_on_homepage', 'is_popular'], 'boolean'],
            [['title', 'image', 'icon'], 'string', 'max' => 255],
            [['tags_field'], 'safe'],
            [['image_field'], 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'webp'], 'maxSize' => 1024*1024*10],
        ];
    }

    /**
     * @return string[]
     */
    public function extraFields()
    {
        return ['places', 'children', 'parent'];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'ID',
            'title' => 'Название',
            'image' => 'Изображение',
            'image_field' => 'Изображение',
            'icon' => 'Иконка',
            'type' => 'Тип',
            'parent_id' => 'Родитель',
            'tags_field' => 'Теги',
            'position' => 'Позиция',
            'is_popular' => 'Популярная',
            'show_on_homepage' => 'Отображать на главной',
            'in_trash' => 'В корзине',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return void
     */
    public function afterFind()
    {
        parent::afterFind();
        /** @todo Зачем? Оптимизировать! */
        foreach ($this->categoryTags as $categoryTag) {
            $this->tags_field[] = $categoryTag->tag_id;
        }
    }

    /**
     * @param $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->uploadImage();
        return parent::beforeSave($insert);
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return void
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function afterSave($insert, $changedAttributes)
    {
        $this->handleTags();
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryTags()
    {
        return $this->hasMany(CategoryTag::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryPlaces()
    {
        return $this->hasMany(PlaceCategory::className(), ['category_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['tag_id' => 'tag_id'])
            ->via('categoryTags');
    }

    /**
     * @return void
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function handleTags() {
        if (!is_array($this->tags_field)) {
            $this->tags_field = [];
            //return;
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

    /**
     * @return Place[]
     */
    public function getPlaces()
    {
        $tagIds = [];
        foreach ($this->categoryTags as $categoryTag) {
            $tagIds[] = $categoryTag->tag_id;
        }

        return Place::find()
            ->joinWith(['placeTags', 'categories'])
            ->where(['in', 'categories.category_id', [$this->category_id]])
            ->orWhere(['in', 'place_tags.tag_id', $tagIds])->all();
    }

    /**
     * @return bool|int|string|null
     */
    public function getCountPlaces()
    {
        return Place::find()
            ->joinWith('categories')
            ->where(['in', 'categories.category_id', [$this->category_id]])
            ->count();
    }

    /**
     *
     */
    public function uploadImage()
    {
        if ($image = UploadedFile::getInstance($this, 'image_field')) {
            $className = 'category';

            do {
                $image_path = '/upload/images/'.strtolower($className).'_'.md5(rand()).'.'.$image->extension;
                $full_path = Yii::getAlias('@frontend_web').$image_path;
            } while (file_exists($full_path));

            $image->saveAs($full_path);

            $this->image = $image_path;
        }
    }

    /**
     *
     */
    public function deleteImage()
    {
        if ($this->image and file_exists(Yii::getAlias('@frontend_web').$this->image)) {
            unlink(Yii::getAlias('@frontend_web').$this->image);
            $this->image = null;
            $this->save(false);
        }
    }

    /**
     * @return array
     */
    public static function getTypesList() {
        return [
            self::TYPE_CATALOG => 'Каталог',
            self::TYPE_ROUTE => 'Маршрут',
            self::TYPE_QUEST => 'Квест',
        ];
    }

    /**
     * @return mixed
     */
    public function getTypeTitle() {
        return self::getTypesList()[$this->type] ?? '-';
    }
}
