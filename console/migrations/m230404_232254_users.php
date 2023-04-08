<?php

use yii\db\Expression;
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
            'email_confirm_token' => $this->string()->unique(),
            'in_trash' => $this->boolean()->defaultValue(false),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);

        $users = [
            ['phone' => '+7 (911) 216-56-19', 'name' => 'Никита Беляев', 'email' => 'flipak180@mail.ru']
        ];

        $now = new Expression('NOW()');
        foreach ($users as $user) {
            $this->insert('users', [
                'phone' => $user['phone'],
                'name' => $user['name'],
                'email' => $user['email'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('users');
    }
}
