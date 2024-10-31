<?php

use yii\db\Migration;

/**
 * Class m241031_200902_tickets
 */
class m241031_200902_tickets extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('tickets', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'type' => $this->smallInteger(),
            'message' => $this->text(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('tickets');
    }
}
