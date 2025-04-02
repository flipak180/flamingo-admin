<?php

use yii\db\Migration;

/**
 * Class m250402_090949_user_categories
 */
class m250402_090949_user_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_categories', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'category_id' => $this->integer(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_categories');
    }
}
