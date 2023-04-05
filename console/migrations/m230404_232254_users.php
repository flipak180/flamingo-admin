<?php

use yii\db\Migration;

/**
 * Class m230404_232254_users
 */
class m230404_232254_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(),
            'phone' => $this->string()->notNull(),
            'name' => $this->string(),
            'email' => $this->string(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'email_confirm_token' => $this->string()->unique(),
            'password_reset_token' => $this->string()->unique(),
            'in_trash' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}
