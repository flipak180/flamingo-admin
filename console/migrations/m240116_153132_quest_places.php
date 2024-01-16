<?php

use yii\db\Migration;

/**
 * Class m240116_153132_quest_places
 */
class m240116_153132_quest_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('quest_places', [
            'id' => $this->primaryKey(),
            'quest_id' => $this->integer()->notNull(),
            'place_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('quest_places');
    }
}
