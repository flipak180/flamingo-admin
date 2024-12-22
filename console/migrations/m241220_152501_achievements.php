<?php

use yii\db\Migration;

/**
 * Class m241220_152501_achievements
 */
class m241220_152501_achievements extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('achievements', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'description' => $this->string(),
            'category_id' => $this->integer(),
            'level' => $this->smallInteger(),
            'points' => $this->integer(),
            'status' => $this->smallInteger(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('achievements');
    }
}
