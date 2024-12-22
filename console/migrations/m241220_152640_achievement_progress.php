<?php

use yii\db\Migration;

/**
 * Class m241220_152640_achievements_progress
 */
class m241220_152640_achievements_progress extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('achievements_progress', [
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
        $this->dropTable('achievements_progress');
    }
}
