<?php

use yii\db\Migration;

/**
 * Class m241021_191242_extend_tags
 */
class m241021_191242_extend_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tags', 'full_title', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241021_191242_extend_tags cannot be reverted.\n";

        return false;
    }
}
