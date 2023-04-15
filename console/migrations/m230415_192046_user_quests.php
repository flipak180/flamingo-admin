<?php

use yii\db\Migration;

/**
 * Class m230415_192046_user_quests
 */
class m230415_192046_user_quests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_quests', [
            'user_quest_id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'quest_id' => $this->integer()->notNull(),
            'stage' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_quests');
    }
}
