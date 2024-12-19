<?php

use yii\db\Migration;

/**
 * Class m241219_142059_push_tokens
 */
class m241219_142059_push_tokens extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('push_tokens', [
            'id' => $this->primaryKey(),
            'token' => $this->string()->notNull(),
            'user_id' => $this->integer(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('push_tokens');
    }
}
