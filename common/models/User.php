<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $user_id
 * @property string $phone
 * @property string|null $name
 * @property string|null $avatar
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Visit[] $visits
 * @property PlaceRate[] $rates
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $avatar_field;

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
            [['phone', 'name', 'avatar'], 'string', 'max' => 255],
            [['phone'], 'unique'],
            [['avatar_field'], 'file', 'extensions' => ['png', 'jpg', 'jpeg'], 'maxSize' => 1024*1024*2],
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
            'avatar' => 'Аватарка',
            'avatar_field' => 'Аватарка',
            'in_trash' => 'В корзине',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return bool
     */
    public function uploadImage($image)
    {
        if (!$image) {
            return true;
        }

        $className = 'avatar';
        $oldAvatar = $this->avatar;

        do {
            $image_path = '/upload/images/'.strtolower($className).'_'.md5(rand()).'.'.$image->extension;
            $full_path = Yii::getAlias('@frontend_web').$image_path;
        } while (file_exists($full_path));

        $image->saveAs($full_path);
        $this->avatar = $image_path;
        unlink(Yii::getAlias('@frontend_web').$oldAvatar);
        return $this->save();
    }

    /**
     * @return true
     */
    public function deleteImage()
    {
        if ($this->avatar && file_exists(Yii::getAlias('@frontend_web').$this->avatar)) {
            unlink(Yii::getAlias('@frontend_web').$this->avatar);
            $this->avatar = null;
            $this->save(false);
        }
        return true;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::className(), ['user_id' => 'user_id'])->orderBy('visit_id DESC');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRates()
    {
        return $this->hasMany(PlaceRate::className(), ['user_id' => 'user_id']);
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
