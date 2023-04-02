<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "categories".
 *
 * @property int $id
 * @property string $title
 * @property string|null $image
 * @property int|null $parent_id
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Place[] $places
 * @property Category $parent
 */
class Category extends \yii\db\ActiveRecord
{
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
            TimestampBehavior::className()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['parent_id', 'in_trash'], 'integer'],
            [['title', 'image'], 'string', 'max' => 255],
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
            'image' => 'Изображение',
            'parent_id' => 'Родитель',
            'in_trash' => 'В корзине',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlaces()
    {
        return $this->hasMany(Place::className(), ['category_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
