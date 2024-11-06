<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $user_id
 * @property string $phone
 * @property string|null $name
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Visit[] $visits
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SALT = '$2y$10$';
    const DEFAULT_NAME = 'Странник';

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
            [['phone', 'name'], 'required'],
            [['in_trash'], 'boolean'],
            [['phone', 'name'], 'string', 'max' => 255],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'ID',
            'phone' => 'Телефон',
            'name' => 'Имя',
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
        return $this->hasMany(Visit::className(), ['user_id' => 'user_id'])->orderBy('visit_id DESC');
    }

    /**
     * @param $phone
     * @return string
     */
    public static function encryptPhone($phone)
    {
        return md5($phone . self::SALT);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return User|IdentityInterface|null
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['phone' => $token]);
    }

    public static function findIdentity($id)
    {
        return static::findOne(['user_id' => $id]);
    }

    public function getId()
    {
        return $this->user_id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }
}
