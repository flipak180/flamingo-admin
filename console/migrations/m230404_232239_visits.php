<?php

use yii\db\Migration;

/**
 * Class m230404_232239_visits
 */
class m230404_232239_visits extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('visits', [
            'id' => $this->primaryKey(),
            'place_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('visits');
    }
}
