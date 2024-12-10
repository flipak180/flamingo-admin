<?php

use yii\db\Migration;

/**
 * Class m241210_190517_stories_views
 */
class m241210_190517_stories_views extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('stories_views', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'story_id' => $this->integer(),
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
        $this->dropTable('stories_views');
    }
}
