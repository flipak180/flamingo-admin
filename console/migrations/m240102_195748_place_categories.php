<?php

use yii\db\Migration;

/**
 * Class m240102_195748_place_categories
 */
class m240102_195748_place_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('place_categories', [
            'id' => $this->primaryKey(),
            'place_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('place_categories');
    }
}
