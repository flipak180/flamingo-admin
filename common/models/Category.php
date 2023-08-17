<?php

namespace common\models;

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
 * @property int $type
 * @property int|null $parent_id
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category $parent
 * @property Category $children
 * @property CategoryTag[] $categoryTags
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'type'], 'required'],
            [['parent_id', 'type'], 'integer'],
            [['in_trash'], 'boolean'],
            [['title', 'image'], 'string', 'max' => 255],
            [['tags_field'], 'safe'],
            [['image_field'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024*1024],
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
            'type' => 'Тип',
            'parent_id' => 'Родитель',
            'tags_field' => 'Теги',
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
        foreach ($this->categoryTags as $categoryTag) {
            $this->tags_field[] = $categoryTag->tag_id;
        }
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
        $this->uploadImage();
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

    /**
     * @return Place[]
     */
    public function getPlaces()
    {
        $tagIds = [];
        foreach ($this->categoryTags as $categoryTag) {
            $tagIds[] = $categoryTag->tag_id;
        }

        return Place::find()->joinWith('placeTags')
            ->where(['category_id' => $this->category_id])
            ->orWhere(['in', 'place_tags.tag_id', $tagIds])->all();
    }

    /**
     *
     */
    public function uploadImage()
    {
        if ($image = UploadedFile::getInstance($this, 'image_field')) {
            $image_path = '/upload/images/category_'.$this->category_id.'.'. $image->extension;
            $image->saveAs(Yii::getAlias('@frontend_web').$image_path);
            $this->image = $image_path;
            $this->save(false);
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
