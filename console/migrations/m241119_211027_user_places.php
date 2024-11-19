<?php

use yii\db\Migration;

/**
 * Class m241119_211027_user_places
 */
class m241119_211027_user_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_places', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'place_id' => $this->integer(),
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
        $this->dropTable('user_places');
    }
}
