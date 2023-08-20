<?php

use yii\db\Migration;

/**
 * Class m230820_211520_images
 */
class m230820_211520_images extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('images', [
            'image_id' => $this->primaryKey(),
            'path' => $this->string()->notNull(),
            'model' => $this->string()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'title' => $this->string(),

            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('images');
    }
}
