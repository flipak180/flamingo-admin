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
 * @property int|null $parent_id
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Category $parent
 * @property CategoryTag[] $categoryTags
 * @property Tag[] $tags
 * @property Place[] $places
 */
class Category extends \yii\db\ActiveRecord
{
    public $tags_field;
    public $image_field;

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
            [['title'], 'required'],
            [['parent_id', 'in_trash'], 'boolean'],
            [['title', 'image'], 'string', 'max' => 255],
            [['tags_field'], 'safe'],
            [['image_field'], 'file', 'extensions' => ['png', 'jpg'], 'maxSize' => 1024*1024],
        ];
    }

    /**
     * @return string[]
     */
    public function extraFields()
    {
        return ['places'];
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
        return $this->hasOne(Category::className(), ['parent_id' => 'category_id']);
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

        return Place::find()->joinWith('placeTags')->where(['in', 'place_tags.tag_id', $tagIds])->all();
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
}
