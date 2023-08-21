<?php

use yii\db\Migration;

/**
 * Class m230813_092227_events
 */
class m230813_092227_events extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('events', [
            'event_id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'subtitle' => $this->string(),
            'place_id' => $this->integer(),
            'description' => $this->text(),

            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('events');
    }
}
