<?php

use yii\db\Migration;

/**
 * Class m250426_081816_peters_eye_users
 */
class m250426_081816_peters_eye_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('peters_eye_users', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'peters_eye_id' => $this->integer(),
            'is_winner' => $this->boolean(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('peters_eye_users');
    }
}
