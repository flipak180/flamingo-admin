<?php

use yii\db\Migration;

/**
 * Class m250425_193504_peters_eyes
 */
class m250425_193504_peters_eyes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('peters_eyes', [
            'id' => $this->primaryKey(),
            'coords' => 'geography',
            'radius' => $this->integer(),
            'prize' => $this->integer(),
            'winner_id' => $this->integer(),
            'win_at' => $this->timestamp(),
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
        $this->dropTable('peters_eyes');
    }
}
