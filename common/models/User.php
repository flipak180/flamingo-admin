<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $phone
 * @property string|null $name
 * @property string|null $email
 * @property string|null $email_confirm_token
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Visit[] $visits
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
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
            [['phone'], 'required'],
            [['in_trash'], 'integer'],
            [['phone', 'name', 'email', 'email_confirm_token'], 'string', 'max' => 255],
            [['email_confirm_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Телефон',
            'name' => 'Имя',
            'email' => 'Email',
            'email_confirm_token' => 'Email Confirm Token',
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
        return $this->hasMany(Visit::className(), ['user_id' => 'id'])->orderBy('id DESC');
    }
}
