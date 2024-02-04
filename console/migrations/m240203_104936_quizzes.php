<?php

use yii\db\Migration;

/**
 * Class m240203_104936_quizzes
 */
class m240203_104936_quizzes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('quizzes', [
            'id' => $this->primaryKey(),
            'type' => $this->smallInteger()->notNull(),
            'question' => $this->string(),
            'answer' => $this->json(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('quizzes');
    }
}
