<?php

use yii\db\Migration;

/**
 * Class m230410_224137_category_tags
 */
class m230410_224137_category_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category_tags', [
            'category_tag_id' => $this->primaryKey(),
            'category_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category_tags');
    }
}
