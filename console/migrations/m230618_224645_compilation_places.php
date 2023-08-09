<?php

use yii\db\Migration;

/**
 * Class m230618_224645_compilation_places
 */
class m230618_224645_compilation_places extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('compilation_places', [
            'compilation_place_id' => $this->primaryKey(),
            'compilation_id' => $this->integer()->notNull(),
            'place_id' => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('compilation_places');
    }
}
