<?php

use yii\db\Migration;

/**
 * Class m241220_152640_achievement_progress
 */
class m241220_152640_achievement_progress extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('achievement_progress', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'achievement_id' => $this->integer()->notNull(),
            'points' => $this->integer(),
            'unlocked_at' => $this->dateTime(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('achievement_progress');
    }
}
