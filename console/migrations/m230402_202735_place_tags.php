<?php

use yii\db\Migration;

/**
 * Class m230402_202735_place_tags
 */
class m230402_202735_place_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('place_tags', [
            'id' => $this->primaryKey(),
            'place_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('place_tags');
    }
}
