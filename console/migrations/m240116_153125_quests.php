<?php

use yii\db\Migration;

/**
 * Class m240116_153125_quests
 */
class m240116_153125_quests extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('quests', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'subtitle' => $this->string(),
            'description' => $this->text(),
            'distance' => $this->integer(),
            'time' => $this->integer(),

            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('quests');
    }
}
