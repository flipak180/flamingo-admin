<?php

use yii\db\Migration;

/**
 * Class m241220_093714_push_notifications
 */
class m241220_093714_push_notifications extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('push_notifications', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'body' => $this->string()->notNull(),
            'link' => $this->string(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('push_notifications');
    }
}
