<?php

namespace common\models;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $phone
 * @property string|null $name
 * @property string|null $email
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $email_confirm_token
 * @property string|null $password_reset_token
 * @property int|null $in_trash
 * @property int $created_at
 * @property int $updated_at
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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'auth_key', 'password_hash'], 'required'],
            [['in_trash'], 'integer'],
            [['phone', 'name', 'email', 'password_hash', 'email_confirm_token', 'password_reset_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email_confirm_token'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phone' => 'Phone',
            'name' => 'Name',
            'email' => 'Email',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'email_confirm_token' => 'Email Confirm Token',
            'password_reset_token' => 'Password Reset Token',
            'in_trash' => 'In Trash',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
