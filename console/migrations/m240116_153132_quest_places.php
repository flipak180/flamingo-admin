<?php

use yii\db\Migration;

/**
 * Class m240116_153132_quest_places
 */
class m240116_153132_quest_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('quest_places', [
            'id' => $this->primaryKey(),
            'quest_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'location' => 'geometry',

//            'locked' => $this->boolean()->defaultValue(false),
//            'question' => $this->text(),
//            'answer' => $this->string(),
//            'quiz_type' => $this->smallInteger(),

            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('quest_places');
    }
}
