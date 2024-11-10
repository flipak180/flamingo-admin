<?php

use yii\db\Migration;

/**
 * Class m241109_100138_rates
 */
class m241109_100138_place_rates extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('place_rates', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'place_id' => $this->integer(),
            'rate' => $this->smallInteger(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('place_rates');
    }
}
