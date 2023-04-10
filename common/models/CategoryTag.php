<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "category_tags".
 *
 * @property int $category_tag_id
 * @property int $category_id
 * @property int $tag_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Category $category
 * @property Tag $tag
 */
class CategoryTag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category_tags';
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
            [['category_id', 'tag_id'], 'required'],
            [['category_id', 'tag_id'], 'default', 'value' => null],
            [['category_id', 'tag_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_tag_id' => 'ID',
            'category_id' => 'Категория',
            'tag_id' => 'Тег',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_tag_id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['category_tag_id' => 'tag_id']);
    }
}
