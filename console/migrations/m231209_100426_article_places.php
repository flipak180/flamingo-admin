<?php

use yii\db\Migration;

/**
 * Class m231209_100426_article_places
 */
class m231209_100426_article_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('article_places', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer()->notNull(),
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
        $this->dropTable('article_places');
    }
}
