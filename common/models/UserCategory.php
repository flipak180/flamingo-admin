<?php

namespace common\models;

use api\models\Categories\CategoryApiItem;
use common\models\Categories\Category;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "user_categories".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $category_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property Category $category
 */
class UserCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_categories';
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
            [['user_id', 'category_id'], 'default', 'value' => null],
            [['user_id', 'category_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['category_id' => 'category_id']);
    }

    /**
     * @param $categoryId
     * @param $userId
     * @return bool|int
     * @throws \Throwable
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     */
    public static function toggleCategory($categoryId, $userId)
    {
        $existingModel = UserCategory::findOne(['category_id' => $categoryId, 'user_id' => $userId]);
        if ($existingModel) {
            return $existingModel->delete();
        } else {
            $model = new UserCategory();
            $model->category_id = $categoryId;
            $model->user_id = $userId;
            return $model->save();
        }
    }

    /**
     * @param $userId
     * @return array
     */
    public static function getCategoryIdsByUserId($userId): array
    {
        return array_map(
            fn($category) => $category->category_id,
            UserCategory::findAll(['user_id' => $userId])
        );
    }

    /**
     * @return array
     */
    public static function getAllCategories()
    {
        $categories = Category::find()
            ->where(['not', ['parent_id' => null]])
            ->andWhere('categories.in_trash IS NOT TRUE')
            ->orderBy('title ASC')
            ->all();

        return array_map(
            fn($category) => CategoryApiItem::from($category),
            $categories
        );
    }
}
